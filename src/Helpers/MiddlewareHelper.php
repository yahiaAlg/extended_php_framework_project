<?php

namespace App\Helpers;
use App\Middleware\CSRFMiddleware;

class MiddlewareHelper
{
    private static $middlewares = [
        'auth' => [AuthenticationHelper::class, 'check'],
        'guest' => [AuthenticationHelper::class, 'guest'],
        'csrf' => [CSRFMiddleware::class, 'middleware'],
    ];

    public static function add($name, $callback)
    {
        self::$middlewares[$name] = $callback;
    }

    public static function run($name, $params = [])
    {
        if (isset(self::$middlewares[$name])) {
            return call_user_func_array(self::$middlewares[$name], $params);
        }
        return true;
    }

    public static function remove($name)
    {
        unset(self::$middlewares[$name]);
    }

    public static function exists($name)
    {
        return isset(self::$middlewares[$name]);
    }
}