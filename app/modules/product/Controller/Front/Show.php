<?php
// app\modules\product\Controller\Front\Index.php
namespace app\modules\product\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\modules\product\Factory\ProductShowViewModelFactory;
use app\modules\product\ViewModel\ProductShowViewModel;


class Show extends Controller
{    
    public function action()
    {        
        $this->setTitle('Product Details');
        $viewModel = $this->createViewModel();
        $viewContent = $this->renderView('product','show', ['viewModel' => $viewModel]);        
        $this->renderLayout('layout', $viewContent);
    }

    protected function createViewModel(): ProductShowViewModel
    {  
        return ProductShowViewModelFactory::create();
    }    
}



