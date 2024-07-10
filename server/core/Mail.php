<?php

class Mail {
    public static function send($to, $subject, $message) {
        $headers = "From: " . getenv('MAIL_USER') . "\r\n";
        $headers .= "Reply-To: " . getenv('MAIL_USER') . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $transport = (new Swift_SmtpTransport(getenv('MAIL_HOST'), getenv('MAIL_PORT')))
            ->setUsername(getenv('MAIL_USER'))
            ->setPassword(getenv('MAIL_PASS'));

        $mailer = new Swift_Mailer($transport);

        $email = (new Swift_Message($subject))
            ->setFrom([getenv('MAIL_USER') => 'No Reply'])
            ->setTo([$to])
            ->setBody($message, 'text/html');

        return $mailer->send($email);
    }
}
