<?php

namespace FileUploader;

use Exception;

class FileUploadException extends Exception
{
    const ERR_FILE_TOO_LARGE = 1;
    const ERR_INVALID_EXTENSION = 2;
    const ERR_UPLOAD_FAILED = 3;
    const ERR_NO_FILE = 4;
    const ERR_PARTIAL_UPLOAD = 5;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fileTooLarge($maxSize)
    {
        return new self("File exceeds the maximum allowed size of {$maxSize} bytes.", self::ERR_FILE_TOO_LARGE);
    }

    public static function invalidExtension($allowedExtensions)
    {
        $extensions = implode(', ', $allowedExtensions);
        return new self("File extension not allowed. Allowed extensions are: {$extensions}.", self::ERR_INVALID_EXTENSION);
    }

    public static function uploadFailed()
    {
        return new self("Failed to move uploaded file.", self::ERR_UPLOAD_FAILED);
    }

    public static function noFile()
    {
        return new self("No file was uploaded.", self::ERR_NO_FILE);
    }

    public static function partialUpload()
    {
        return new self("The uploaded file was only partially uploaded.", self::ERR_PARTIAL_UPLOAD);
    }
}