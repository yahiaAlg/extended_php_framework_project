<?php

namespace FileUploader;

class FileUploader
{
    private $uploadDirectory = IMAGE_UPLOAD_DIR;
    private $allowedExtensions= ALLOWED_IMAGE_TYPES;
    private $maxFileSize = MAX_FILE_SIZE;

    public function __construct(string $uploadDirectory, array $allowedExtensions = [], int $maxFileSize = 5242880)
    {
        $this->uploadDirectory = rtrim($uploadDirectory, '/') . '/';
        $this->allowedExtensions = $allowedExtensions;
        $this->maxFileSize = $maxFileSize; // Default to 5MB
    }

    public function upload(array $file): string
    {
        $this->validate($file);

        $fileName = $this->generateUniqueFilename($file['name']);
        $destination = $this->uploadDirectory . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw FileUploadException::uploadFailed();
        }

        return $fileName;
    }

    private function validate(array $file): void
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new FileUploadException("Invalid file parameter.");
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw FileUploadException::fileTooLarge($this->maxFileSize);
            case UPLOAD_ERR_PARTIAL:
                throw FileUploadException::partialUpload();
            case UPLOAD_ERR_NO_FILE:
                throw FileUploadException::noFile();
            default:
                throw new FileUploadException("Unknown error occurred.");
        }

        if ($file['size'] > $this->maxFileSize) {
            throw FileUploadException::fileTooLarge($this->maxFileSize);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!empty($this->allowedExtensions) && !in_array($extension, $this->allowedExtensions)) {
            throw FileUploadException::invalidExtension($this->allowedExtensions);
        }
    }

    private function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        return $basename . '.' . $extension;
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }
}