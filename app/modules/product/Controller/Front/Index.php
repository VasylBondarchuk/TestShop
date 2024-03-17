<?php
// app\modules\product\Controller\Front\Index.php
namespace app\modules\product\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\modules\product\Model\ProductResourceModel;
use app\modules\product\ViewModel\ProductIndexViewModel;

class Index extends Controller
{
    public function action()
    {        
        $this->setTitle('Products');
        $viewModel = $this->createViewModel();
        $viewContent = $this->renderView('index', ['viewModel' => $viewModel]);        
        $this->renderLayout('layout', $viewContent);
    }

    protected function createViewModel(): ProductIndexViewModel
    {
        $title = "Products";
        $productsCollection = $this->getProductsCollection(); // Retrieve product data
        return new ProductIndexViewModel($title, $productsCollection);
    }

    protected function getProductsCollection()
    {
        $productResourceModel = new ProductResourceModel();
        return $productResourceModel->getProductCollection();
    }
}



