<?php
// app\modules\product\Factory\ProductIndexViewModelFactory.php
namespace app\modules\product\Factory;

use app\modules\product\Model\ProductRepository;
use app\modules\product\Model\ProductResourceModel;
use app\core\Logger;

class ProductRepositoryFactory
{
    public static function create(): ProductRepository
    {
        $productResourceModel = new ProductResourceModel();
        $logger = new Logger();
        return new ProductRepository($productResourceModel,$logger );
    }
}
