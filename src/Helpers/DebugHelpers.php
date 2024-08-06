<?php

namespace Helpers;

class DebugHelpers
{
    public static function dump($var, $die = false)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';

        if ($die) {
            die();
        }
    }

    public static function dd($var)
    {
        self::dump($var, true);
    }

    public static function backtrace()
    {
        $backtrace = debug_backtrace();
        echo '<pre>';
        foreach ($backtrace as $index => $trace) {
            $file = isset($trace['file']) ? $trace['file'] : 'Unknown file';
            $line = isset($trace['line']) ? $trace['line'] : 'Unknown line';
            $function = isset($trace['function']) ? $trace['function'] : 'Unknown function';
            echo "#$index $file($line): $function()\n";
        }
        echo '</pre>';
    }

    public static function logMessage($message, $level = 'info')
    {
        $logFile = __DIR__ . '/../../logs/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] [$level] $message\n";
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}