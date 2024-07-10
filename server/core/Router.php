<?php

class Router {
    private $routes = [];
    private $namedRoutes = [];
    private $scopes = [];

    public function addRoute($method, $route, $callback, $name = null, $scopes = []) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'callback' => $callback,
            'scopes' => $scopes
        ];
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
    }

    public function addRoutes($routes, $prefix = '') {
        foreach ($routes as $route) {
            $method = $route['method'];
            $uri = $prefix . $route['route'];
            $callback = $route['callback'];
            $name = $route['name'] ?? null;
            $scopes = $route['scopes'] ?? [];
            $this->addRoute($method, $uri, $callback, $name, $scopes);

            if (isset($route['children'])) {
                $this->addRoutes($route['children'], $uri);
            }
        }
    }

    public function resolve($url, $method) {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($this->convertToRegex($route['route']), $url, $matches)) {
                array_shift($matches);
                if ($this->checkScopes($route['scopes'])) {
                    if (is_callable($route['callback'])) {
                        return call_user_func_array($route['callback'], $matches);
                    } elseif (is_string($route['callback'])) {
                        $parts = explode('@', $route['callback']);
                        $controller = $parts[0];
                        $method = $parts[1];
                        // Ensure the controller file is included
                        $controllerFile = '../app/controllers/' . $controller . '.php';
                        if (file_exists($controllerFile)) {
                            require_once $controllerFile;
                        } else {
                            throw new Exception("Controller file $controllerFile not found");
                        }

                        $controller = new $controller;
                        return call_user_func_array([$controller, $method], $matches);
                    }
                } else {
                    header("HTTP/1.0 403 Forbidden");
                    echo "403 Forbidden";
                    return;
                }
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    private function convertToRegex($route) {
        $route = preg_replace('/\{(\w+)\}/', '(\w+)', $route);
        return "@^" . $route . "$@";
    }

    private function checkScopes($scopes) {
        if (empty($scopes)) {
            return true;
        }

        foreach ($scopes as $scope) {
            if (!isset($_SESSION['scopes']) || !in_array($scope, $_SESSION['scopes'])) {
                return false;
            }
        }

        return true;
    }

    public function generate($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception("No route found with the name '{$name}'");
        }
        $route = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $route = str_replace("{" . $key . "}", $value, $route);
        }
        return getenv('BASE_URL') . $route;
    }
}
