<?php

spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    echo "<br/>the currently called class: $class<br/>";
    $prefixes = ['Core\\', 'App\\'];

    // Base directory for the namespace prefix
    $currentPrefix = explode("\\", $class)[0];
    $base_dir = $currentPrefix == 'App'? BASEROOT : LIBSROOT;
    echo '<br/>Base directory for the namespace prefix: '.$currentPrefix.' is: '.$base_dir.'<br/>';

    // Does the class use the namespace prefix?
    
    if (strpos($class, $currentPrefix) === 0) {
        // Get the relative class name
        $relative_class = substr($class, strlen($currentPrefix));
        echo '<br/>Relative class name: '.$relative_class.'<br/>';
        // Include the file

        $file = $base_dir ."/".rtrim($currentPrefix,'\\'). str_replace('\\', '/', $relative_class) . '.php';
        echo '<br/>File to include: '.$file.'<br/>';
        if (file_exists($file)) {
            require $file;
            return;
        }
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