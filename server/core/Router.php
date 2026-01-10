<?php

namespace core;

use Exception;
use core\View;


class NotFoundException extends Exception
{
    /**
     * Exception thrown when a route is not found.
     * 
     * Just a wrapper around the standard Exception, but specific to 404s.
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = 'Not Found', $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Route class
 * 
 * Represents a single route definition.
 * Holds all the config for a route like method, path, callback, etc.
 * We use this object to pass data around easly.
 */
class Route
{
    public string $method;
    public string $route;
    public string $callback;
    public string $name;
    public array $patterns = [];
    public array $pass = [];
    public array $middleware = [];

    /**
     * @param array $patterns
     */
    public function setPatterns(array $patterns)
    {
        $this->patterns = $patterns;
        return $this;
    }

    /**
     * @param array $pass
     */
    public function setPass(array $pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @param array $middleware
     */
    public function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }
}

/**
 * Router class
 * 
 * This class is used to handle all routing logic.
 * It's inspired by the Router class from cakephp.
 * We define routes, and then resolve URLs to those routes.
 */
class Router
{
    private array $routes = [];
    private array $namedRoutes = [];
    private array $globalMiddleware = [];

    /**
     * Sets middleware that runs on every request.
     * 
     * Usefull for things like CSRF protection, session starting, etc.
     * Things that need to happen no matter what page you're on.
     * 
     * @param array $middleware
     */
    public function setGlobalMiddleware(array $middleware)
    {
        $this->globalMiddleware = $middleware;
        return $this;
    }

    /**
     * Connects a new route.
     * 
     * Creates a Route object and adds it to our list.
     * We convert the simple route sintax (like /users/{id}) to regex for matching later.
     * 
     * @param string $route
     * @param mixed $callback
     * @param string|null $name
     */
    public function connect($route, $callback, $name = null)
    {
        $routeObj = new Route();
        // We store as regex for later calling the 
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

    /**
     * Converts a route string to a regular expression.
     * 
     * We replace parameters like {id} with named capture groups.
     * This allows us to extract the values later when matching.
     * 
     * 
     * Example:
     * /post/{id} -> @^/post/(?P<id>[^/]+)$@
     * 
     * @param string $route
     * @return string
     */
    private function convertToRegex($route)
    {
        return '@^' . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route) . '$@';
    }

    /**
     * Resolves a URL to a route callback.
     * 
     * This is the majic part. We loop through all routes and try to match the URL.
     * If we match, we check method, patterns, run middleware, and finally call the controller.
     * If nothing matches, 404.
     * 
     * @param string $url
     * @param string $method
     */
    public function resolve($url, $method)
    {
        foreach ($this->routes as $route) {
            // Check if the URL matches the route. A match is beign detected by comparingn it to the regex returned by convertToRegex.
            if (preg_match($route->route, $url, $matches)) {
                $params = [];
                // Extract the values from the URL and store them in the $params array.
                foreach ($route->pass as $param) {
                    if (isset($matches[$param])) {
                        $params[] = $matches[$param];
                    }
                }
                // Check if method is assigned to the route and if it matches the request method.
                if (isset($route->patterns['method']) && $method != $route->patterns['method']) {
                    $this->renderErrorPage(405, 'Method Not Allowed');
                    return;
                }
                // Check if the URL matches the route patterns.
                // This allows us to validate params like {id} to be a number, etc.
                if (!empty($route->patterns)) {
                    foreach ($route->patterns as $key => $pattern) {
                        if (isset($matches[$key]) && !preg_match('@^' . $pattern . '$@', $matches[$key])) {
                            $this->renderErrorPage(404, 'Page Not Found');
                            return;
                        }
                    }
                }
                // Run global middleware first. In this case we load the CSRF protection middleware.
                if (!empty($this->globalMiddleware)) {
                    foreach ($this->globalMiddleware as $middleware) {
                        $middlewareInstance = new $middleware();
                        // If the middleware returns false, we stop the request.
                        if (!$middlewareInstance->handle()) {
                            return;
                        }
                    }
                }
                // Run route-specific middleware
                if (!empty($route->middleware)) {
                    foreach ($route->middleware as $middleware) {
                        // Most middleware in case of failure, it will do a redirect by using header and then exit. So it will never reach return;...
                        $middleware = new $middleware();
                        if (!$middleware->handle()) {
                            return;
                        }
                    }
                }
                // If all passes, we invoke the callback.
                return $this->invokeCallback($route->callback, $params);
            }
        }
        $this->renderErrorPage(404, 'Page Not Found');
    }

    private function renderErrorPage($code, $message, $details = null)
    {
        http_response_code($code);
        $view = new View($this);
        $view->render('errors/' . $code, compact('message', 'details'));
    }

    /**
     * Invokes the callback for a matched route.
     * 
     * Handles both closure callbacks and controller@action strings.
     * Instantiates the controller and calls the method with params.
     * 
     * @param mixed $callback
     * @param array $params
     */
    private function invokeCallback($callback, $params)
    {
        if (is_callable($callback)) {
            // If the callback is a callable, we just call it with the params.
            return call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            // If the callback is a string, we assume it's a controller@action string.
            try {
                return $this->callControllerMethod($callback, $params);
            } catch (NotFoundException $e) {
                $this->renderErrorPage(404, 'Not Found');
            } catch (Exception $e) {
                $this->renderErrorPage(500, 'Internal Server Error', $e->getMessage());
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

    /**
     * Generates a URL for a named route.
     * 
     * Reverse routing! We take a route name and params, and give you back the URL.
     * Super usefull so we don't hardcode URLs in views.
     * 
     * @param string $name
     * @param array $params
     * @return string
     */
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
