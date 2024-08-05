<?php

require_once __DIR__ . '/../src/FileUploader/FileUploader.php';
require_once __DIR__ . '/../src/FileUploader/FileUploadException.php';

use FileUploader\FileUploader;
use FileUploader\FileUploadException;

// Create a temporary upload directory for testing
$uploadDir = __DIR__ . '/temp_uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

$fileUploader = new FileUploader($uploadDir, ['jpg', 'png'], 1048576); // 1MB max

function testFileUpload($fileUploader) {
    // Simulate file upload
    $file = [
        'name' => 'test.jpg',
        'type' => 'image/jpeg',
        'size' => 500000,
        'tmp_name' => __DIR__ . '/test.jpg',
        'error' => UPLOAD_ERR_OK
    ];

    // Create a test file
    file_put_contents($file['tmp_name'], 'Test content');

    try {
        $result = $fileUploader->upload($file);
        assert(file_exists($fileUploader->getUploadDirectory() . $result), 'Uploaded file should exist');
        echo "File upload test passed.\n";
    } catch (FileUploadException $e) {
        echo "Test failed: " . $e->getMessage() . "\n";
    } finally {
        // Clean up
        @unlink($fileUploader->getUploadDirectory() . $result);
        @unlink($file['tmp_name']);
    }
}

function testInvalidExtension($fileUploader) {
    $file = [
        'name' => 'test.txt',
        'type' => 'text/plain',
        'size' => 100,
        'tmp_name' => __DIR__ . '/test.txt',
        'error' => UPLOAD_ERR_OK
    ];

    try {
        $fileUploader->upload($file);
        echo "Test failed: Expected FileUploadException\n";
    } catch (FileUploadException $e) {
        assert($e->getCode() === FileUploadException::ERR_INVALID_EXTENSION, 'Should have invalid extension error');
        echo "Invalid extension test passed.\n";
    }
}

function testFileTooLarge($fileUploader) {
    $file = [
        'name' => 'large.jpg',
        'type' => 'image/jpeg',
        'size' => 2000000, // 2MB, larger than our 1MB limit
        'tmp_name' => __DIR__ . '/large.jpg',
        'error' => UPLOAD_ERR_OK
    ];

    try {
        $fileUploader->upload($file);
        echo "Test failed: Expected FileUploadException\n";
    } catch (FileUploadException $e) {
        assert($e->getCode() === FileUploadException::ERR_FILE_TOO_LARGE, 'Should have file too large error');
        echo "File too large test passed.\n";
    }
}

// Run tests
try {
    testFileUpload($fileUploader);
    testInvalidExtension($fileUploader);
    testFileTooLarge($fileUploader);
    echo "All tests passed successfully.\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}

// Clean up temporary upload directory
rmdir($uploadDir);