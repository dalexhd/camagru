<?php

namespace app\middlewares;

use core\Middleware;
use core\Session;

class AuthMiddleware extends Middleware
{
	public function handle()
	{
		// Check if the user is logged in
		if (!isset($_SESSION['user_id'])) {
			Session::setFlash('error', 'Please login to access this page');
			header('Location: /login' . '?redirect=' . urlencode($_SERVER['REQUEST_URI']));
			exit;
		}
		return true;
	}
}
