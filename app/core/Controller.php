<?php

/**
 * Class Controller
 */
class Controller {

    // Заголовок сторінки
    protected $title = null;
    // Назва вигляду
    protected $view = null;
    // Масив для виводу данних у вигляди (views)

    protected $registry = [];
    // Ім'я моделі, що буде використовуватися
    protected $modelName = '';

    // Метод отримання назви моделі
    public function getModelName() {
        return $this->modelName;
    }

    // Метод встановлення назви моделі
    public function setModelName($modelName) {
        $this->modelName = $modelName;
    }

    // Метод отримання назви сторінки
    public function getTitle(): string {
        return $this->title;
    }

    // Метод встановлення назви вигляду
    public function setView($view = null) {
        if ($view === null) {
            $view = Route::getAction();
        }
        $this->view = $view;
    }

    // Метод отримання назви вигляду
    public function getView() {
        return $this->view === null ? '404' : $this->view;
    }

    public function renderPartialview($view_name) {
        $view_path = ROOT . '/app/layouts/' . $view_name . '.php';
        if (file_exists($view_path)) {
            include $view_path;
        }
    }

    // Метод відображення вигляду
    public function renderView() {
        $controller = $this->getView() === "404" ? 'error' : Route::getController();
        $view_path = ROOT . '/app/views/' . strtolower($controller) . '/' . strtolower($this->getView()) . '.php';
        if (file_exists($view_path)) {
            include $view_path;
        }
    }
    
    // Метод відображення вигляду
    public function renderMessageView() {        
        $view_path = ROOT . '/app/views/message/message.php';
        if (file_exists($view_path)) {
            include $view_path;
        }
    }

    // Метод відображення шаблону
    public function renderLayout($layout = "layout") {
        if (file_exists(ROOT . '/app/layouts/' . $layout . '.php')) {
            include ROOT . '/app/layouts/' . $layout . '.php';
        }
    }

    // Метод 
    public function getHomePageURL() {
        $protocol = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $protocol = 'https';
        } 
        $serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL);
        return $serverName ? "$protocol://" . $serverName : '';
    }

    function __call($name, $args) {
        switch (TRUE) {
            case substr($name, -6) == "Action";
                $this->setView('404');
                $this->renderLayout('layout_404');
                exit();
            case (substr($name, 0, 3) == "get" && ctype_upper(substr($name, 3, 1)));
                $property = lcfirst(substr($name, 3));
                return $this->$property;
                break;
            case (substr($name, 0, 3) == "set" && ctype_upper(substr($name, 3, 1)));
                $property = lcfirst(substr($name, 3));
                $this->$property = $args[0];
                break;
            default;
                return FALSE;
        }
    }

    public function getModel(string $name, ...$constructorParameters) {
    // Create an object of the class $name with the given constructor parameters
    $model = new $name(...$constructorParameters);
    
    // Call the method to set the model name
    $this->setModelName(get_class($model));
    
    return $model;
}
    
    // Повертає назву id-колонки
    public function getIdColumnName(string $name) {
        return $this->getModel($name)->getIdColumn();
    }    
}
