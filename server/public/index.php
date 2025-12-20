<?php

use core\Session;

require_once '../core/Database.php';
require_once '../core/Migration.php';
require_once '../core/Security.php';
require_once '../core/View.php';
require_once '../core/Response.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../core/Mail.php';
require_once '../core/Middleware.php';
require_once '../core/Router.php';
require_once '../core/File.php';
require_once '../core/Session.php';
require_once '../core/ImageProcessor.php';
require_once '../core/Helpers.php';
require_once '../config/routes.php';

// Dynamically include all model files
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

// Dynamically include all middleware files
foreach (glob("../middlewares/*.php") as $filename) {
    require_once $filename;
}

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
