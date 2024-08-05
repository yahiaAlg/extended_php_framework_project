<?php

namespace Core\Router;

class Route
{
    protected $method;
    protected $path;
    protected $controller;
    protected $action;
    protected $middleware = [];
    protected $params = [];

    public function __construct($method, $path, $controller, $action, $middleware = []) {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->middleware = $middleware;
    }

    public function match($method, $uri)
    {
        if ($this->method !== $method) {
            return false;
        }

        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $this->path);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    public function execute()
    {
        $controller = new $this->controller();
        return call_user_func_array([$controller, $this->action], $this->params);
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}