<?php
// app\modules\product\Controller\Front\Index.php
namespace app\modules\category\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\modules\category\Model\CategoryResourceModel;
use app\modules\category\ViewModel\CategoryIndexViewModel;

class Index extends Controller
{
    public function action()
    {   
        $this->setTitle('Categories');
        $viewModel = $this->createViewModel();
        $viewContent = $this->renderView('category','index', ['viewModel' => $viewModel]);        
        $this->renderLayout('layout', $viewContent);
    }

    protected function createViewModel(): CategoryIndexViewModel
    {        
        $categoryCollection = $this->getCategoryCollection(); // Retrieve product data
        return new CategoryIndexViewModel($categoryCollection);
    }

    protected function getCategoryCollection()
    {
        $categoryResourceModel = new CategoryResourceModel();
        return $categoryResourceModel->getCategoryCollection();
    }
}



