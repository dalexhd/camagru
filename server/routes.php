<?php

$router = new Router();

$routes = [
    [
        'method' => 'GET',
        'route' => '/',
        'callback' => 'HomeController@index',
        'name' => 'home'
    ],
    [
        'method' => 'GET',
        'route' => '/login',
        'callback' => 'AuthController@showLogin',
        'name' => 'login.show'
    ]
];

$router->addRoutes($routes);
