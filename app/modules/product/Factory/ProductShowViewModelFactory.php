<?php
// app\modules\product\Factory\ProductIndexViewModelFactory.php
namespace app\modules\product\Factory;

use app\modules\product\Factory\ProductRepositoryFactory;
use app\modules\product\ViewModel\ProductShowViewModel;

class ProductShowViewModelFactory
{
    public static function create(): ProductShowViewModel
    {
        $productRepositoryfactory = new ProductRepositoryFactory();        
        return new ProductShowViewModel($productRepositoryfactory);
    }
}
