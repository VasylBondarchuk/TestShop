<?php
// app\modules\product\Factory\ProductIndexViewModelFactory.php
namespace app\modules\product\Factory;

use app\modules\product\ViewModel\ProductIndexViewModel;
use app\modules\product\Model\ProductResourceModel;

class ProductIndexViewModelFactory
{
    public static function create(): ProductIndexViewModel
    {
        $productResourceModel = new ProductResourceModel();
        $productCollection = $productResourceModel->getProductCollection();
        return new ProductIndexViewModel($productCollection);
    }
}
