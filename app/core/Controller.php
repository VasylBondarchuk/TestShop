<?php
namespace app\core;

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

public function renderLayout($layout = 'layout', $viewContent = null) {
    // Construct the layout file path
    $layoutFilePath = ROOT . '/app/layouts/' . $layout . '.php';    
    // Check if the layout file exists
    if (file_exists($layoutFilePath)) {       
        
        // Start output buffering
        ob_start();

        // Include the layout file
        include $layoutFilePath;

        // Get the layout content and clear the buffer
        $layoutContent = ob_get_clean();

        // Replace the placeholder with the view content
        echo str_replace('{{ content }}', $viewContent, $layoutContent);
    } else {
        echo "Layout file does not exist!";
    }
}

protected function renderView(string $moduleName, string $viewName, array $params = []): string {
    ob_start(); // Start output buffering to capture the view content
    $viewModel = $params['viewModel'] ?? null; // Extract the ViewModel if provided
    $viewFile = ROOT . "/app/modules/$moduleName/view/front/$viewName.php";
    if (file_exists($viewFile)) {
        if ($viewModel !== null) {
            // Extract the ViewModel object to make it available in the view
            $viewModelVarName = 'viewModel';
            $$viewModelVarName = $viewModel; // Variable variable to dynamically set the variable name
        }
        extract($params); // Extract other parameters to make them available in the view
        include $viewFile;
    } else {
        echo 'View file not found: ' . $viewName;
    }
    return ob_get_clean(); // Return the captured view content and clear the buffer
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

    
    public function action(){
        $this->indexAction();
    }
    
    private function indexAction(){
         Helper::redirect('/category/index');
    }   
    
}
