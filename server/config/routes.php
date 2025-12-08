<?php

use core\Router;
use app\middlewares\AuthMiddleware;

$router = new Router();

// Post routes
$router->connect(
	'/',
	['controller' => 'PostController', 'action' => 'index'],
	'home'
);

$router->connect(
	'/post/{id}',
	['controller' => 'PostController', 'action' => 'index'],
	'post_view'
)
	->setPass(['id'])
	->setPatterns(['id' => '[0-9]+']);

$router->connect(
	'/create',
	['controller' => 'PostController', 'action' => 'create'],
	'create'
)->setMiddleware([AuthMiddleware::class]);

// Post comment interaction routes
$router->connect(
	'/comment/interact',
	['controller' => 'PostCommentInteractionController', 'action' => 'create'],
	'post_comment_interaction_create'
)->setMiddleware([AuthMiddleware::class]);

// Post like routes
$router->connect(
	'/post/{id}/like',
	['controller' => 'PostLikeController', 'action' => 'toggle'],
	'post_like_toggle'
)->setPass(['id'])
	->setPatterns(['id' => '[0-9]+']);

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
)->setMiddleware([AuthMiddleware::class]);

$router->connect(
	'/settings',
	['controller' => 'UserController', 'action' => 'settings'],
	'settings'
)->setMiddleware([AuthMiddleware::class]);

$router->connect(
	'/settings/account',
	['controller' => 'UserController', 'action' => 'accountSettings'],
	'accountSettings'
)->setMiddleware([AuthMiddleware::class]);

$router->connect(
	'/settings/security',
	['controller' => 'UserController', 'action' => 'securitySettings'],
	'securitySettings'
)->setMiddleware([AuthMiddleware::class]);

$router->connect(
	'/users/{nickname}',
	['controller' => 'UserController', 'action' => 'view'],
	'user_view'
)->setPatterns(['nickname' => '[a-zA-Z0-9]+'])->setMiddleware([AuthMiddleware::class]);

// Search routes
$router->connect(
	'/search',
	['controller' => 'SearchController', 'action' => 'index'],
	'search'
);

// Post comment routes
$router->connect(
	'/post/{id}/comment',
	['controller' => 'PostCommentController', 'action' => 'create'],
	'post_comment_create'
)
	->setPass(['id'])
	->setPatterns(['id' => '[0-9]+']);

// Api routes
$router->connect(
	'/api/posts/{page}/{limit}',
	['controller' => 'PostController', 'action' => 'posts'],
	'posts'
)
	->setPass(['page', 'limit'])
	->setPatterns(['page' => '[0-9]+', 'limit' => '[0-9]+']);
