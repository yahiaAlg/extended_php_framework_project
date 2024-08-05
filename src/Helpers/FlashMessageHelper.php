<?php

namespace App\Helpers;

class FlashMessageHelper
{
    private static $flashKey = 'flash_messages';

    public static function set($message, $type = 'info')
    {
        SessionHelper::start();
        $flash = SessionHelper::get(self::$flashKey, []);
        $flash[] = ['message' => $message, 'type' => $type];
        SessionHelper::set(self::$flashKey, $flash);
    }

    public static function get()
    {
        SessionHelper::start();
        $flash = SessionHelper::get(self::$flashKey, []);
        SessionHelper::remove(self::$flashKey);
        return $flash;
    }

    public static function hasMessages()
    {
        SessionHelper::start();
        return SessionHelper::has(self::$flashKey) && !empty(SessionHelper::get(self::$flashKey));
    }

    public static function display()
    {
        $messages = self::get();
        $output = '';
        foreach ($messages as $message) {
            $output .= "<div class='alert alert-{$message['type']}'>{$message['message']}</div>";
        }
        return $output;
    }
}