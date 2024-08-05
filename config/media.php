<?php

// Define upload directories
define('UPLOAD_DIR', APPROOT . '/public/uploads');

// Define allowed file types and their directories
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('IMAGE_UPLOAD_DIR', UPLOAD_DIR . '/images');

define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'txt']);
define('DOCUMENT_UPLOAD_DIR', UPLOAD_DIR . '/documents');

define('ALLOWED_VIDEO_TYPES', ['mp4', 'avi', 'mov']);
define('VIDEO_UPLOAD_DIR', UPLOAD_DIR . '/videos');

// Maximum file size (in bytes)
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// You can add more media-related configurations here