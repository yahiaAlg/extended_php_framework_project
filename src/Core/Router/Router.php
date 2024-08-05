<?php

namespace Core\Router;

use App\Helpers\MiddlewareHelper;

class Router
{
    protected $routes = [];
    protected $namedRoutes = [];
    protected $basePath = '';

    public function __construct()
    {
        // set the base path as the APPROOT
        $this->basePath = APPROOT;
        // message for showcasing the launching of the router
        echo "<br/>Router is launched.<br/>";
    }
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function add($methods, $uri, $handler, $middleware = [], $name = null)
    {
        $uri = $this->basePath . '/' . trim($uri, '/');
        $uri = $uri === $this->basePath ? $uri : rtrim($uri, '/');

        foreach ((array)$methods as $method) {
            $this->routes[$method][$uri] = ['handler' => $handler, 'middleware' => $middleware];
        }

        if ($name) {
            $this->namedRoutes[$name] = $uri;
        }
    }

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

    public function any($uri, $handler, $middleware = [], $name = null)
    {
        $this->add(['GET', 'POST', 'PUT', 'DELETE'], $uri, $handler, $middleware, $name);
    }

    public function dispatch($uri, $method)
    {
        // removing the query string and extracting only the url
        // $uri = explode('?', $uri)[0];
        $uri = $this->removeQueryString($uri);
        //echoing the current uri
        $routeRegistered = isset($this->routes[$method]);
        echo "<br/>Current uri dispatched: $uri<br/> and the current method used is $method<br/>";
        echo "<pre>";
        print_r($this->routes);
        echo "</pre>";
        if ($routeRegistered) {
        echo "<pre>";
        print_r($this->routes[$method]);
        echo "</pre>";
        }
        if (
            !isset($this->routes[$method][$uri])
        ) {
            // echoing the routes array
            $this->notFound();
        }

        foreach ($this->routes[$method] as $route => $info) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $uri, $params)) {
                array_shift($params);
                $this->runMiddleware($info['middleware']);
                return $this->runHandler($info['handler'], $params);
            }
        }

        $this->notFound();
    }

    protected function convertRouteToRegex($route)
    {
        return '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route) . '$#';
    }

    protected function removeQueryString($uri)
    {
        return preg_replace('/\?.*/', '', $uri);
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