<?php
namespace app\core;


ini_set('display_errors', 1);
error_reporting(E_ALL);

class Route
{   
    
    /**
     * @return mixed|string
     */
    public static function getBP()
    {
        return self::getBasePath();
    }

    /**
     * @return array|false|string|string[]
     */
    public static function getBasePath()
    {
        $documentRoot = filter_var($_SERVER['DOCUMENT_ROOT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $base_path = substr(ROOT, strlen($documentRoot));
        if (DS !== '/') {
            $base_path = str_replace(DS,'/', $base_path);
        }
        return $base_path;
    }    

    
    protected static function includeController($controllerPath)
    {
        if (file_exists($controllerPath)) {            
            include $controllerPath;
            return true;
        }
        return false;
    }

    protected static function instantiateController($moduleName, $controllerName)
    {
        $controllerClass = 'app\\modules\\' . ucfirst($moduleName) . '\\Controller\\Front\\' . ucfirst($controllerName);
        
        if (class_exists($controllerClass)) {
            return new $controllerClass();
        } else {
            echo "Controller class not found!\n"; // Log error or handle gracefully
            return null;
        }
    }

    protected static function handle404()
    {
        echo "Controller file not found!\n"; // Log error
        // You can include a default error controller here
    }

    protected static function getControllerPath($moduleName, $controllerName)
    {
        return ROOT . '/app/modules/' . $moduleName . '/Controller/Front/' . ucfirst($controllerName) . '.php';
    }

    protected static function handleRootURL()
    {
        // Use the core Controller for the root URL
        include ROOT . '/app/core/Controller.php'; // Include the core Controller file

        // Instantiate the core Controller
        $controllerClass = 'app\\core\\Controller';
        $controller = new $controllerClass();

        // Call the action method
        $controller->action();
    }

    protected static function handleNonRootURL($uri)
{
    // Extract the route parts from the URI
    $routeParts = explode('/', trim(parse_url($uri, PHP_URL_PATH), '/'));
    
    // If the first part of the URI is not empty, use it as the module name
    // Otherwise, default to 'default' module
    $moduleName = !empty($routeParts[0]) ? $routeParts[0] : 'default';
    
    // Remove the module name from the route parts
    array_shift($routeParts); 

    // If there are remaining parts in the route, use the first part as the controller name
    // Otherwise, default to 'Index' controller
    $controllerName = !empty($routeParts) ? $routeParts[0] : 'Index';    
    // Construct the controller path
    $controllerPath = self::getControllerPath($moduleName, $controllerName);    
    if (self::includeController($controllerPath)) {
        $controller = self::instantiateController($moduleName, $controllerName);        
        if ($controller) {
            // Call the action method
            $controller->action();
        }
    } else {
        self::handle404();
    }
}


    protected static function getRoute($uri)
    {
        if ($uri === '/' || empty($uri)) {
            // Handle root URL
            self::handleRootURL();
        } else {
            // Handle non-root URL
            self::handleNonRootURL($uri);
        }
    }

    public static function Start()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        self::getRoute($requestUri);
    }
}

