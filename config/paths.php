<?php

// Define APPROOT
define('APPROOT', dirname(dirname(__FILE__))."/App");
define('LIBSROOT', dirname(dirname(__FILE__))."/src");
define('BASEROOT', dirname(dirname(__FILE__)));


// Define URLROOT
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
define('URLROOT', $protocol . "://" . $host);

// Define public directory
define('PUBLIC_DIR', BASEROOT . '/public');

// Define views directory
define('VIEWS_DIR', APPROOT . '/views');

// Define controllers directory
define('CONTROLLERS_DIR', APPROOT . '/controllers');

// Define models directory
define('MODELS_DIR', APPROOT . '/models');


// echoing the different Constants
echo "APPROOT: " . APPROOT . "<br>";
echo "URLROOT: " . URLROOT . "<br>";
echo "PUBLIC_DIR: " . PUBLIC_DIR . "<br>";



