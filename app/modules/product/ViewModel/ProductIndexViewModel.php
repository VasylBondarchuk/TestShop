<?php
// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\product\ViewModel;

use app\core\Helper;

class ProductIndexViewModel
{    
    private array $productsCollection;

    public function __construct(array $productsCollection)
    {        
        $this->productsCollection = $productsCollection;
    }
   

    public function getProductsCollection(): array
    {
        return $this->productsCollection;
    }
    
    public function getProductsInCategory(): array
    {
        $categoryId = Helper::getQueryParam('category_id');
        $productsInCategory = array_filter($this->productsCollection, function ($product) use ($categoryId) {
            return $product->isProductInCategory($product->getProductId(), $categoryId);
        });

        return $productsInCategory;
    } 

}
