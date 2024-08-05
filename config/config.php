<?php

// Load all configuration files
require_once 'paths.php';
require_once 'database.php';
require_once 'media.php';

// Application configuration
define('SITE_NAME', 'Your MVC Framework');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// You can add more global configurations here