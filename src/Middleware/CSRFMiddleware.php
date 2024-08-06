<?php

namespace Middleware;

use Helpers\SessionHelper;

class CSRFMiddleware
{
    private static $tokenName = 'csrf_token';
    private static $headerName = 'X-CSRF-TOKEN';

    public static function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        SessionHelper::set(self::$tokenName, $token);
        return $token;
    }

    public static function getToken()
    {
        if (!SessionHelper::has(self::$tokenName)) {
            return self::generateToken();
        }
        return SessionHelper::get(self::$tokenName);
    }

    public static function validateToken($token = null)
    {
        if ($token === null) {
            $token = $_POST[self::$tokenName] ?? $_SERVER['HTTP_' . str_replace('-', '_', strtoupper(self::$headerName))] ?? null;
        }

        if (!$token || $token !== SessionHelper::get(self::$tokenName)) {
            http_response_code(403);
            die('CSRF token validation failed');
        }

        return true;
    }

    public static function csrfField()
    {
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . self::getToken() . '">';
    }

    public static function middleware()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::validateToken();
        }
    }
}