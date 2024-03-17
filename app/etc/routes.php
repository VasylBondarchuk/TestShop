<?php

// Define an array to store all route configurations
$mergedRoutes = [];

// Scan the modules directory for module directories
$moduleDirectories = glob(ROOT . '/app/modules/*', GLOB_ONLYDIR);

// Loop through each module directory
foreach ($moduleDirectories as $moduleDir) {
    // Check if the module has a routes.php file
    $routesFilePath = $moduleDir . '/config/routes.php';
    if (file_exists($routesFilePath)) {
        // Include the routes.php file and retrieve its route definitions
        $moduleRoutes = include $routesFilePath;
        
        // Merge the module routes into the mergedRoutes array
        $mergedRoutes = array_merge($mergedRoutes, $moduleRoutes);
    }
}

// Return the merged routes
return $mergedRoutes;
