<?php

namespace core;

class Security
{
    public static function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword($password)
    {
        return password_hash($password . getenv('SERVER_APP_SALT'), PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password . getenv('SERVER_APP_SALT'), $hash);
    }

    public static function generateCSRFToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
