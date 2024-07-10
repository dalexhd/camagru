<?php

use core\Router;

$router = new Router();

$router->connect(
	'/',
	['controller' => 'HomeController', 'action' => 'index'],
	'home'
);

$router->connect(
	'/login',
	['controller' => 'AuthController', 'action' => 'login'],
	'login'
);

$router->connect(
	'/register',
	['controller' => 'AuthController', 'action' => 'register'],
	'register'
);

$router->connect(
	'/logout',
	['controller' => 'AuthController', 'action' => 'logout'],
	'logout'
);
