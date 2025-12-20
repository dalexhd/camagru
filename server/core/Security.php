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

    public static function encrypt($data)
    {
        $key = getenv('SERVER_ENCRYPTION_KEY');
        $iv = substr(hash('sha256', getenv('SERVER_ENCRYPTION_IV')), 0, 16);
        return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    }

    public static function decrypt($data)
    {
        $key = getenv('SERVER_ENCRYPTION_KEY');
        $iv = substr(hash('sha256', getenv('SERVER_ENCRYPTION_IV')), 0, 16);
        return openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);
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

    public static function isValidPassword($password)
    {
        // At least 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            return false;
        }
        return true;
    }
}
