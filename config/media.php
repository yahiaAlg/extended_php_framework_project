<?php

// Define upload directories
define('UPLOAD_DIR', PUBLIC_DIR . '/uploads');
// make the directory if it doesn't exist
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
    }
// } else {
//     // if the directory exists, remove any files in it
//     $files = scandir(UPLOAD_DIR);
//     foreach ($files as $file) {
//         if ($file != '.' && $file != '..') {
//             unlink(UPLOAD_DIR . '/' . $file);
//         }
//     }
// }


// Define allowed file types and their directories
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('IMAGE_UPLOAD_DIR', UPLOAD_DIR . '/images');
// make the directory if it doesn't exist
if (!is_dir(IMAGE_UPLOAD_DIR)) {
    mkdir(IMAGE_UPLOAD_DIR, 0777, true);
}

define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'txt']);
define('DOCUMENT_UPLOAD_DIR', UPLOAD_DIR . '/documents');
// make the directory if it doesn't exist
if (!is_dir(DOCUMENT_UPLOAD_DIR)) {
    mkdir(DOCUMENT_UPLOAD_DIR, 0777, true);
}

define('ALLOWED_VIDEO_TYPES', ['mp4', 'avi', 'flv']);
define('VIDEO_UPLOAD_DIR', UPLOAD_DIR . '/videos');
// make the directory if it doesn't exist
if (!is_dir(VIDEO_UPLOAD_DIR)) {
    mkdir(VIDEO_UPLOAD_DIR, 0777, true);
}
// Maximum file size (in bytes)
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// You can add more media-related configurations here