<?php

require_once '../config.php';
require_once '../core/EnvLoader.php';
require_once '../core/Database.php';
require_once '../core/Migration.php';
require_once '../core/Security.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../core/Mail.php';
require_once '../core/Router.php';
require_once '../core/Session.php';
require_once '../routes.php';

Session::init();

$url = isset($_SERVER['REQUEST_URI']) ? rtrim($_SERVER['REQUEST_URI'], '/') : '/';
if ($url == '') {
    $url = '/';
}

// Remove the query string if it exists
if (($pos = strpos($url, '?')) !== false) {
    $url = substr($url, 0, $pos);
}
$method = $_SERVER['REQUEST_METHOD'];

$router->resolve($url, $method);
