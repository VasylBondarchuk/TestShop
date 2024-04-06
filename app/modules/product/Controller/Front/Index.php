<?php
// app\modules\product\Controller\Front\Index.php
namespace app\modules\product\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\modules\product\Model\ProductResourceModel;
use app\modules\product\ViewModel\ProductIndexViewModel;
use app\modules\product\Factory\ProductIndexViewModelFactory;

class Index extends Controller
{
    public function action()
    {        
        $this->setTitle('Products');
        $viewModel = $this->createViewModel();
        $viewContent = $this->renderView('product', 'index', ['viewModel' => $viewModel]);        
        $this->renderLayout('layout', $viewContent);
    }

    protected function createViewModel(): ProductIndexViewModel
    {   
        $productsCollection = $this->getProductsCollection(); // Retrieve product data
        return ProductIndexViewModelFactory::create($productsCollection);
    }

    protected function getProductsCollection()
    {
        $productResourceModel = new ProductResourceModel();
        return $productResourceModel->getProductCollection();
    }
}



