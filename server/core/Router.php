<?php

namespace core;

use Exception;
use core\View;


class NotFoundException extends Exception
{
    public function __construct($message = 'Not Found', $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class Route
{
    public $method;
    public $route;
    public $callback;
    public $name;
    public $patterns = [];
    public $pass = [];
    public $middleware = [];

    public function setPatterns(array $patterns)
    {
        $this->patterns = $patterns;
        return $this;
    }

    public function setPass(array $pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }
}

class Router
{
    private $routes = [];
    private $namedRoutes = [];

    public function connect($route, $callback, $name = null)
    {
        $routeObj = new Route();
        $routeObj->route = $this->convertToRegex($route);
        $routeObj->callback = "{$callback['controller']}@{$callback['action']}";
        $routeObj->name = $name;
        $this->routes[] = $routeObj;
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $routeObj;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    private function convertToRegex($route)
    {
        return '@^' . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route) . '$@';
    }

    public function resolve($url, $method)
    {
        foreach ($this->routes as $route) {
            if (preg_match($route->route, $url, $matches)) {
                $params = [];
                foreach ($route->pass as $param) {
                    if (isset($matches[$param])) {
                        $params[] = $matches[$param];
                    }
                }
                if (isset($route->patterns['method']) && $method != $route->patterns['method']) {
                    $this->renderErrorPage(405, 'Method Not Allowed');
                    return;
                }
                if (!empty($route->patterns)) {
                    foreach ($route->patterns as $key => $pattern) {
                        if (isset($matches[$key]) && !preg_match('@^' . $pattern . '$@', $matches[$key])) {
                            $this->renderErrorPage(404, 'Page Not Found');
                            return;
                        }
                    }
                }
                if (!empty($route->middleware)) {
                    foreach ($route->middleware as $middleware) {
                        $middleware = new $middleware();
                        if (!$middleware->handle()) {
                            return;
                        }
                    }
                }
                return $this->invokeCallback($route->callback, $params);
            }
        }
        $this->renderErrorPage(404, 'Page Not Found');
    }

    private function renderErrorPage($code, $message)
    {
        http_response_code($code);
        $view = new View($this);
        $view->render('errors/' . $code, ['message' => $message]);
    }

    private function invokeCallback($callback, $params)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            try {
                return $this->callControllerMethod($callback, $params);
            } catch (NotFoundException $e) {
                $this->renderErrorPage(404, 'Not Found');
            } catch (Exception $e) {
                print_r($e);
                $this->renderErrorPage(500, 'Internal Server Error' . $e->getMessage());
            }
        }
    }

    private function callControllerMethod($callback, $params)
    {
        list($controller, $action) = explode('@', $callback);
        if (!file_exists('../controllers/' . $controller . '.php')) {
            throw new NotFoundException();
        }
        require_once '../controllers/' . $controller . '.php';
        $controller = new $controller($this);
        if (!method_exists($controller, $action)) {
            throw new Exception();
        }
        return call_user_func_array([$controller, $action], $params);
    }

    public function generate($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception("No route found with the name '{$name}'");
        }
        $route = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $route = str_replace("{" . $key . "}", $value, $route);
        }
        return $route;
    }
}
