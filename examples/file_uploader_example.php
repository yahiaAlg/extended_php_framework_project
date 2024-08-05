<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/paths.php';
require_once __DIR__ . '/../config/media.php';

use FileUploader\FileUploader;
use FileUploader\FileUploadException;

// Create an instance of FileUploader
$uploader = new FileUploader(IMAGE_UPLOAD_DIR, ALLOWED_IMAGE_TYPES, MAX_FILE_SIZE);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    try {
        $fileName = $uploader->upload($_FILES['file']);
        $message = "File uploaded successfully. New filename: " . $fileName;
    } catch (FileUploadException $e) {
        $message = "Error: " . $e->getMessage();
    } catch (Exception $e) {
        $message = "An unexpected error occurred: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Example</title>
</head>
<body>
    <h1>File Upload Example</h1>
    
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Test Cases</h2>
    <ul>
        <li>Upload a valid image file (e.g., JPEG, PNG, GIF)</li>
        <li>Try uploading a file larger than the maximum allowed size</li>
        <li>Attempt to upload a file with an invalid extension (e.g., .txt)</li>
        <li>Submit the form without selecting a file</li>
        <li>Upload a partial file (you may need to simulate this by modifying the script)</li>
    </ul>
</body>
</html>