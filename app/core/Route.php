<?php

/**
 * Class Route
 */
class Route
{

    /**
     * @var null
     */
    private static $controller = null;
    /**
     * @var null
     */
    private static $action = null;

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
        $base_path = substr(ROOT, strlen($_SERVER['DOCUMENT_ROOT']));
        if (DS !== '/') {
            $base_path = str_replace(DS,'/', $base_path);
        }
        return $base_path;
    }

    /**
     * @param $uri
     */
    protected static function getRoute($uri)
    {
         $route = substr($uri, strlen(self::getBasePath()));
         $route_array = explode('/', $route);
         if ($route_array[0] === "") {
              array_shift($route_array);
         }
         if (isset($route_array[0]) && $route_array[0] === 'index.php') {
              array_shift($route_array);
         }
         self::$controller = !empty($route_array[0]) ? $route_array[0] : 'index';
         self::$action = !empty($route_array[1]) ? $route_array[1] : 'index';
    }

    /**
     *
     */
    public static function Start()
    {
           
            // витягуємо маршрут із строки запроса
            $request = explode('?', $_SERVER['REQUEST_URI']);
            $uri = $request[0];
            
            self::getRoute($uri);

            // визначаємо імена класу контролера та методу екшен
            $controller_name = ucfirst(self::$controller) .'Controller';
            $action_name = self::$action . 'Action';
            
            if(file_exists(ROOT . '/app/controllers/'. $controller_name .'.php')) {
                include ROOT . '/app/controllers/'. $controller_name .'.php';
            }
            else {
                include ROOT . '/app/controllers/ErrorController.php';
                $controller_name = 'ErrorController';
            }

            $controller = new $controller_name();
            $controller->$action_name();
     }

    /**
     * @return null
     */
    public static function getAction()
     {
        return self::$action;
     }

    /**
     * @return null
     */
    public static function getController()
     {
        return self::$controller;
     }

}
