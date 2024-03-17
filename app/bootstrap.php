<?php
use app\core\Route;
/**
 * Bootstrap file responsible for initializing the application.
 */
// Include the Route class
include_once ROOT . '/app/core/Route.php';

// Include the configuration file
include_once ROOT . DS . 'app' . DS . 'etc' . DS . 'config.php';

function capitalizeLastPathComponent($filePath) {
    $parts = explode('/', $filePath);
    $lastPart = end($parts);
    $capitalizedLastPart = ucfirst($lastPart);
    array_pop($parts);
    array_push($parts, $capitalizedLastPart);
    return implode('/', $parts);
}

// Register the autoloader for classes
spl_autoload_register(function ($className) {
    // Convert namespace separators to directory separators
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);    
    // Define the base directory for classes
    $baseDir = ROOT . DIRECTORY_SEPARATOR;    

    // Check if the class file exists and include it
    if (strpos($classPath, 'app\core\\') === 0) {
        // Core class
        $filePath = $baseDir . 'core' . DIRECTORY_SEPARATOR . substr($classPath, strlen('app\core\\')) . '.php';        
    } elseif (strpos($classPath, 'app\modules\\') === 0) {
        // Module class
        $filePath = $baseDir . 'modules' . DIRECTORY_SEPARATOR . substr($classPath, strlen('app\modules\\')) . '.php';        
    } else {
        // Other classes
        $filePath = $baseDir . $classPath . '.php';
        // Adjust path if necessary
    }      
    
    if (file_exists($filePath)) {
        include_once $filePath;
    }
});

// Start the session
session_start();

// Start the routing process
Route::Start();
