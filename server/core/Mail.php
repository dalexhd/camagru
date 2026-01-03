<?php

namespace core;

class Mail
{
    /**
     * Send an email
     * 
     * Why not use directly the mail function? Well it doesn't work on the server. You need in-build SMTP server to make it work.
     * That's why we use this class. It's a simple SMTP client that uses the SMTP protocol to send emails.
     * 
     * How it works:
     * 1. Connect to the SMTP server
     * 2. Send the EHLO command
     * 3. If TLS is enabled, send the STARTTLS command
     * 4. If authentication is enabled, send the AUTH LOGIN command
     * 5. Send the MAIL FROM command
     * 6. Send the RCPT TO command
     * 7. Send the DATA command
     * 8. Send the message
     * 9. Send the QUIT command
     * 
     * Commands:
     * - EHLO: Extended Hello
     * - STARTTLS: Start TLS
     * - AUTH LOGIN: Authentication
     * - MAIL FROM: Mail From
     * - RCPT TO: Recipient To
     * - DATA: Data
     * - QUIT: Quit
     * More info: https://tools.ietf.org/html/rfc5321
     * Sample taken from: https://www.fast2host.com/knowledgebase/article/277/php-mail-script-with-smtp-authentication/
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public static function send(string $to, string $subject, string $message): bool
    {
        try {
            $config = self::getConfig();
            $socket = self::connect($config);

            self::handshake($socket, $config);
            self::authenticate($socket, $config);
            self::sendMail($socket, $config['from'], $to, $subject, $message);
            self::quit($socket);

            return true;
        } catch (\Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }

    private static function getConfig(): array
    {
        return [
            'host' => getenv('SMTP_HOST') ?: 'smtp.example.com',
            'port' => (int) (getenv('SMTP_PORT') ?: 587),
            'user' => getenv('SMTP_USER') ?: '',
            'pass' => getenv('SMTP_PASSWORD') ?: '',
            'from' => getenv('SMTP_FROM') ?: 'no-reply@example.com',
            'tls' => getenv('SMTP_TLS') !== 'off',
            'auth' => getenv('SMTP_AUTH') !== 'off',
        ];
    }

    private static function connect(array $config)
    {
        $socket = fsockopen($config['host'], $config['port'], $errno, $errstr, 10);
        if (!$socket) {
            throw new \Exception("Connect Failed: $errstr ($errno)");
        }
        self::readResponse($socket); // Banner
        return $socket;
    }

    private static function handshake($socket, array $config): void
    {
        self::sendCommand($socket, "EHLO " . gethostname());

        if ($config['tls']) {
            self::sendCommand($socket, "STARTTLS");
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new \Exception("TLS Handshake Failed");
            }
            self::sendCommand($socket, "EHLO " . gethostname());
        }
    }

    private static function authenticate($socket, array $config): void
    {
        if ($config['auth'] && $config['user'] && $config['pass']) {
            self::sendCommand($socket, "AUTH LOGIN");
            self::sendCommand($socket, base64_encode($config['user']));
            self::sendCommand($socket, base64_encode($config['pass']));
        }
    }

    private static function sendMail($socket, string $from, string $to, string $subject, string $body): void
    {
        self::sendCommand($socket, "MAIL FROM: <$from>");
        self::sendCommand($socket, "RCPT TO: <$to>");
        self::sendCommand($socket, "DATA");

        $headers = "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $payload = "$headers\r\n$body\r\n.";
        self::sendCommand($socket, $payload, false);
    }

    private static function quit($socket): void
    {
        fwrite($socket, "QUIT\r\n");
        fclose($socket);
    }

    private static function sendCommand($socket, string $command, bool $checkResponse = true): void
    {
        fwrite($socket, $command . "\r\n");
        if ($checkResponse) {
            $response = self::readResponse($socket);
            if (substr($response, 0, 1) >= '4') {
                throw new \Exception("Command Failed: $command -> $response");
            }
        }
    }

    private static function readResponse($socket): string
    {
        $response = "";
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) == " ") {
                break;
            }
        }
        return $response;
    }
}
