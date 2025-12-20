<?php

namespace app\middlewares;

use core\Middleware;
use core\Security;
use core\Session;

class CsrfMiddleware extends Middleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!Security::verifyCSRFToken($token)) {
                Session::setFlash('error', 'Invalid CSRF Token. Please try again.');
                // Redirect back to the previous page or home
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header("Location: $referer");
                exit;
            }
        }
        return true;
    }
}
