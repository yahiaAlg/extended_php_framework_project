<?php

namespace Helpers;

class ErrorHelpers
{
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $errorType = self::getErrorType($errno);
        $message = "$errorType: $errstr in $errfile on line $errline";

        if (ini_get('display_errors')) {
            echo "<pre>$message</pre>";
        }

        if (ini_get('log_errors')) {
            error_log($message);
        }

        if ($errno == E_USER_ERROR) {
            exit(1);
        }

        return true;
    }

    public static function handleException($exception)
    {
        $message = "Uncaught Exception: " . $exception->getMessage() . 
                   " in " . $exception->getFile() . 
                   " on line " . $exception->getLine();

        if (ini_get('display_errors')) {
            echo "<pre>$message\n" . $exception->getTraceAsString() . "</pre>";
        }

        if (ini_get('log_errors')) {
            error_log($message);
        }

        exit(1);
    }

    private static function getErrorType($errno)
    {
        switch ($errno) {
            case E_ERROR: return 'E_ERROR';
            case E_WARNING: return 'E_WARNING';
            case E_PARSE: return 'E_PARSE';
            case E_NOTICE: return 'E_NOTICE';
            case E_CORE_ERROR: return 'E_CORE_ERROR';
            case E_CORE_WARNING: return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: return 'E_COMPILE_WARNING';
            case E_USER_ERROR: return 'E_USER_ERROR';
            case E_USER_WARNING: return 'E_USER_WARNING';
            case E_USER_NOTICE: return 'E_USER_NOTICE';
            case E_STRICT: return 'E_STRICT';
            case E_RECOVERABLE_ERROR: return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: return 'E_DEPRECATED';
            case E_USER_DEPRECATED: return 'E_USER_DEPRECATED';
            default: return 'Unknown error type';
        }
    }
}