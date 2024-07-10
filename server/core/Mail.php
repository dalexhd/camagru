<?php

namespace core;

class Mail
{
    public static function send($to, $subject, $message)
    {
        $headers = "From: " . getenv('MAIL_USER') . "\r\n";
        $headers .= "Reply-To: " . getenv('MAIL_USER') . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $message, $headers);
    }
}
