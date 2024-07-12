<?php

namespace app\middlewares;

use core\Middleware;

class AuthMiddleware extends Middleware
{
	public function handle()
	{
		if (!isset($_SESSION['user'])) {
			header('Location: /login');
			exit;
		}
		return true;
	}
}
