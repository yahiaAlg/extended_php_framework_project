<?php

namespace Helpers;

class AuthenticationHelper
{
    private static $userKey = 'authenticated_user';

    public static function login($user)
    {
        SessionHelper::set(self::$userKey, $user);
    }

    public static function logout()
    {
        SessionHelper::remove(self::$userKey);
    }

    public static function isLoggedIn()
    {
        return SessionHelper::has(self::$userKey);
    }

    public static function getUser()
    {
        return SessionHelper::get(self::$userKey);
    }

    public static function getUserId()
    {
        $user = self::getUser();
        return $user ? $user['id'] : null;
    }

    public static function check()
    {
        if (!self::isLoggedIn()) {
            FlashMessageHelper::set('Please log in to access this page.', 'warning');
            header('Location: /login');
            exit;
        }
    }

    public static function guest()
    {
        if (self::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
    }
}