<?php

namespace core;

class Mail
{
    public static function send($to, $subject, $message)
    {
        $headers = "From: " . (getenv('MAIL_USER') ?? 'no-reply@example.com') . "\r\n";
        $headers .= "Reply-To: " . (getenv('MAIL_USER') ?? 'no-reply@example.com') . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $success = mail($to, $subject, $message, $headers);
        if (!$success) {
            echo "Failed to send email to $to with subject $subject reason: " . print_r(error_get_last(), true);
        }
        return $success;
    }
}
