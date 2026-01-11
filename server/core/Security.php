<?php

namespace core;

/**
 * Security class
 * 
 * This class is used to handle security related tasks.
 * It protects our application from common security vulnerabilities.
 * - Passwords: Hashing and verification
 * - Encryption: Encrypting and decrypting data
 * - CSRF: Preventing Cross-Site Request Forgery
 * - XSS: Preventing Cross-Site Scripting attacks
 */
class Security
{
    /**
     * Hash a password
     * 
     * We need to store the password in the database in a secure way. That's why we use the password_hash function.
     * It uses the bcrypt algorithm to hash the password. And we add a salt to the password to make it more secure.
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password . getenv('SERVER_APP_SALT'), PASSWORD_DEFAULT);
    }

    /**
     * Verify a password
     * 
     * Basically it checks if the password is correct.
     * In order to do that, it uses the password_verify function, which compares the hashed password with the password the user entered.
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password . getenv('SERVER_APP_SALT'), $hash);
    }

    /**
     * Encrypt data
     * 
     * We need to encrypt data in order to store it in the database.
     * We use the openssl_encrypt function to encrypt the data.
     * 
     * @param string $data
     * @return string
     */
    public static function encrypt($data)
    {
        $key = getenv('SERVER_ENCRYPTION_KEY');
        $iv = substr(hash('sha256', getenv('SERVER_ENCRYPTION_IV')), 0, 16);
        return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     * Decrypt data
     * 
     * Reverses the encryption process.
     * We use the openssl_decrypt function to decrypt the data.
     * 
     * @param string $data
     * @return string
     */
    public static function decrypt($data)
    {
        $key = getenv('SERVER_ENCRYPTION_KEY');
        $iv = substr(hash('sha256', getenv('SERVER_ENCRYPTION_IV')), 0, 16);
        return openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     * Generate a CSRF token
     * 
     * CSRF tokens are used to prevent Cross-Site Request Forgery attacks.
     * This type of attacks works in the following way:
     * - The attacker sends a request to the server with a forged token.
     * - The server processes the request as if it was a valid request.
     * 
     * To prevent this, we generate a random token and store it in the session.
     * Then we compare the token in the session with the token in the request.
     * This way we can prevent CSRF attacks.
     * 
     * @return string
     */
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

    /**
     * Verify a CSRF token
     * 
     * We compare the token in the session with the token in the request.
     * If they match, we return true, otherwise we return false.
     * 
     * @param string $token
     * @return bool
     */
    public static function verifyCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Check if a password is valid
     * 
     * We check if the password is valid by checking:
     * - At least 8 characters
     * - At least one uppercase letter
     * - At least one lowercase letter
     * - At least one number
     * - At least one special character
     * 
     * @param string $password
     * @return bool
     */
    public static function isValidPassword($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            return false;
        }
        return true;
    }

    /**
     * Sanitize user input
     * 
     * Removes all HTML tags and potentially dangerous characters from user input in order to prevent XSS attacks.
     * 
     * @param string $input The input string to sanitize
     * @return string The sanitized string
     */
    public static function sanitizeInput($input)
    {
        if (!is_string($input)) {
            return $input;
        }

        // Remove all HTML tags
        $input = strip_tags($input);

        // Trim whitespace
        $input = trim($input);

        // Remove null bytes
        $input = str_replace("\0", '', $input);

        return $input;
    }

    /**
     * Escape output for HTML context
     * 
     * Escapes special characters to prevent XSS attacks when displaying user data.
     * This converts characters like <, >, &, ", ' to their HTML entities.
     * 
     * @param mixed $output The data to escape
     * @return string The escaped string safe for HTML output
     */
    public static function escapeOutput($output)
    {
        return htmlspecialchars((string) $output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
