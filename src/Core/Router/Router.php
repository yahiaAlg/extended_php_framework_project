<?php

namespace Core\Router;

use App\Helpers\MiddlewareHelper;

class Router
{
    protected $routes = [];
    protected $namedRoutes = [];
    protected $basePath = '';

    public function __construct($basePath='')
    {
        // set the base path as the APPROOT
        $this->basePath = $basePath;
        // message for showcasing the launching of the router
        echo "<br/>Router is launched.<br/>";
    }
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }


    public function add($method, $uri, $handler, $middleware = [])
    {
        $this->routes[$method][$uri] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    // public function add($methods, $uri, $handler, $middleware = [], $name = null)
    // {
    //     $uri = $this->basePath . '/' . trim($uri, '/');
    //     $uri = $uri === $this->basePath ? $uri : rtrim($uri, '/');

    //     foreach ((array)$methods as $method) {
    //         $this->routes[$method][$uri] = ['handler' => $handler, 'middleware' => $middleware];
    //     }

    //     if ($name) {
    //         $this->namedRoutes[$name] = $uri;
    //     }
    // }

    public function get($uri, $handler, $middleware = [], $name = null)
    {
        $this->add('GET', $uri, $handler, $middleware, $name);
    }

    public function post($uri, $handler, $middleware = [], $name = null)
    {
        $this->add('POST', $uri, $handler, $middleware, $name);
    }

    public function put($uri, $handler, $middleware = [], $name = null)
    {
        $this->add('PUT', $uri, $handler, $middleware, $name);
    }

    public function delete($uri, $handler, $middleware = [], $name = null)
    {
        $this->add('DELETE', $uri, $handler, $middleware, $name);
    }


    public function dispatch($uri, $method)
    {
        // Step 1: Get the requested URI and HTTP method
        echo "Dispatching: $method $uri\n";

        // Step 2: Clean up the URI
        $uri = $this->cleanUri($uri);
        echo "Cleaned URI: $uri\n";

        // Step 3: Look through our routes
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $info) {
                if ($this->matchRoute($route, $uri, $params)) {
                    // Step 4: If we find a matching route
                    echo "Route matched: $route<br/>";

                    // Run any middleware
                    $this->runMiddleware($info['middleware']);

                    // Execute the associated handler
                    return $this->runHandler($info['handler'], $params);
                }
            }
        }

        // Step 5: If no match is found, return a 404 error
        $this->notFound();
    }


    protected function cleanUri($uri)
    {
        // Remove base path from URI
        $uri = substr($uri, strlen($this->basePath));
        
        // Remove query string
        $uri = preg_replace('/\?.*/', '', $uri);
        
        // Ensure URI starts with '/' and remove trailing '/'
        return '/' . trim($uri, '/');
    }

    protected function matchRoute($route, $uri, &$params)
    {
        $pattern = $this->convertRouteToRegex($route);
        if (preg_match($pattern, $uri, $matches)) {
            $params = array_slice($matches, 1);
            return true;
        }
        return false;
    }

    protected function convertRouteToRegex($route)
    {
        return '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route) . '$#';
    }

    protected function runMiddleware($middleware)
    {
        foreach ((array)$middleware as $m) {
            MiddlewareHelper::run($m);
        }
    }

    protected function runHandler($handler, $params)
    {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            // echoing the name of the currently launched controller and it's associated method
            $controller = 'App\\Controllers\\' . $controller;
            echo "<br/>Controller: $controller, Method: $method <br/>";
            $controller = new $controller();
            return call_user_func_array([$controller, $method], $params);
        }

        throw new \Exception('Invalid route handler');
    }

    protected function notFound()
    {
        http_response_code(404);
        echo '404 Not Found';
        exit;
    }

    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new \Exception("Route '{$name}' not found.");
        }

        $url = $this->namedRoutes[$name];

        foreach ($params as $key => $value) {
            $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
    }
}