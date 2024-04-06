<?php
// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\product\ViewModel;

use app\modules\product\Model\Product;
use app\modules\product\Factory\ProductRepositoryFactory;
use app\core\Helper;

class ProductShowViewModel
{    
    private ProductRepositoryFactory $productRepositoryFactory;

    public function __construct(ProductRepositoryFactory $productRepositoryFactory)
    {        
        $this->productRepositoryFactory = $productRepositoryFactory;
    }    

    public function getProduct(): ?Product
    {        
        $productId = (int)Helper::getQueryParam('product_id');               
        $productRepository = $this->productRepositoryFactory::create();
        $product = $productRepository->getById($productId);
        return $product;
    }   
    
}
