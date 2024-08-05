<?php

spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'Core\\';

    // Base directory for the namespace prefix
    $base_dir = LIBSROOT;
    echo '<br/>Base directory for the namespace prefix: '.$base_dir.'<br/>';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        echo "No, move to the next registered autoloader <br/>";
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir .'/Core/'. str_replace('\\', '/', $relative_class) . '.php';
    echo '<br/>File: '.$file.'<br/>';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Function to load helper files
function load_helpers() {
    $helper_dir = __DIR__ . '/../src/Helpers/';
    $helper_files = glob($helper_dir . '*.php');
    foreach ($helper_files as $file) {
        echo "Loading helper: " . basename($file) . "\n";
        require_once $file;
    }
}

// Load all helper files
load_helpers();