<?php

use core\Router;

$router = new Router();


// Post routes
$router->connect(
	'/',
	['controller' => 'PostController', 'action' => 'index'],
	'home'
);

$router->connect(
	'/create',
	['controller' => 'PostController', 'action' => 'create'],
	'create'
);


// Auth routes
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

// User routes
$router->connect(
	'/profile',
	['controller' => 'UserController', 'action' => 'profile'],
	'profile'
);

$router->connect(
	'/settings',
	['controller' => 'UserController', 'action' => 'settings'],
	'settings'
);

$router->connect(
	'/users/{nickname}',
	['controller' => 'UserController', 'action' => 'view'],
	'user_view'
)->setPatterns(['nickname' => '[a-zA-Z0-9]+']);

// Search routes
$router->connect(
	'/search',
	['controller' => 'SearchController', 'action' => 'index'],
	'search'
);
