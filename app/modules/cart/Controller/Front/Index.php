<?php
// app\modules\cart\Controller\Front\Index.php
namespace app\modules\cart\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\modules\cart\ViewModel\CartIndexViewModel;
use app\modules\cart\Factory\CartIndexViewModelFactory;

class Index extends Controller
{
    public function action()
    {   
        $this->setTitle('Cart');        
        $viewModel = $this->createViewModel();
        $viewContent = $this->renderView('cart','index', ['viewModel' => $viewModel]);       
        $this->renderLayout('layout', $viewContent);
    }

    protected function createViewModel(): CartIndexViewModel
    {  
        return CartIndexViewModelFactory::create();
    }    
}



