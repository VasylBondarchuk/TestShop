<?php

/**
 * Bootstrap file responsible for initializing the application.
 */
// Include the configuration file
include_once ROOT . DS . 'app' . DS . 'etc' . DS . 'config.php';

// Register an autoloader for classes in the core directory
spl_autoload_register(function ($className) {
    // Construct the file path for the core class
    $coreFilePath = ROOT . DS . 'app' . DS . 'core' . DS . ucfirst($className) . '.php';

    // Check if the core class file exists
    if (file_exists($coreFilePath)) {
        // Include the core class file
        include_once $coreFilePath;
    }
});

// Register an autoloader for classes in the models directory
spl_autoload_register(function ($class_name) {
    // Construct the file path for the models class
    $modelsFilePath = ROOT . DS . 'app' . DS . 'models' . DS . ucfirst($class_name) . '.php';

    // Check if the class has not been defined and the models class file exists
    if (!class_exists($class_name) && file_exists($modelsFilePath)) {
        // Include the models class file
        include_once $modelsFilePath;
    }
});

// Start the session
session_start();

// Start the routing process
Route::Start();
