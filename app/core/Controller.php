<?php

/**
 * Class Controller
 */
class Controller {
    
    protected $title = null;

    protected $view = null;    

    protected array $registry = [];
    
    protected string $modelName = '';
    
    protected array $messages = [];

    
    public function getModelName() {
        return $this->modelName;
    }
    
    public function setModelName($modelName) {
        $this->modelName = $modelName;
    }
    
    public function getTitle(): string {
        return $this->title;
    }
    
    public function setView($view = null) {
        if ($view === null) {
            $view = Route::getAction();
        }
        $this->view = $view;
    }
    
    public function getView() {
        return $this->view === null ? '404' : $this->view;
    }
    
    public function getMessages() : array {
        return $this->messages;
    }
    
    public function setMessages() {
        $this->messages[] = Helper::getPostValue('error');
    }
    
    public function retrieveMessages() : ?string{
        return Helper::getPostValue('error');
        
    }
    public function renderPartialview($view_name) {
        $view_path = ROOT . '/app/layouts/' . $view_name . '.php';
        if (file_exists($view_path)) {
            include $view_path;
        }
    }

    
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

    /**
     * Retrieves the base URL of the home page.
     * 
     * @return string The base URL of the home page.
     */
    public function getHomePageBaseUrl(): string {
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
    
    
    public function getIdColumnName(string $name) {
        return $this->getModel($name)->getIdColumn();
    }

    public function addToCart(Product $product): void {        
        $cartManger = $this->getModel('CartManager');
        $cartItem = $this->getModel(
                'CartItem',
                $product->getProductId(),
                $product->getSku(),
                $product->getName(),
                $product->getPrice(),
                (int)Helper::getPostValue('qty'),
                $product->getProductImage()
        );
        $cartManger->addItem($cartItem);
    }
}
