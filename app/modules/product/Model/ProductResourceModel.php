<?php
namespace app\modules\product\Model;

use app\core\ResourceModel;
use app\modules\product\Model\Product;
use app\core\DataMapper;

class ProductResourceModel extends ResourceModel
{    
    
    public function __construct()
    {        
        // Specify the table name for the Product model
        parent::__construct(Product::TABLE_NAME, Product::PRODUCT_ID);
    }

    public function getProductCollection() : array
    {
        // Fetch all products using the fetchAll() method from the parent ResourceModel class
        $productsData = $this->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $product = $this->mapProductDataToModel($productData);
            $products[] = $product;
        }
        return $products;
    } 
    
    public function fetchProductById(int $productId): ?Product
    {
        $productData = $this->fetchById($productId);
        if (!$productData) {
            return null;
        }        
        return $this->mapProductDataToModel($productData);
    }

    private function mapProductDataToModel(array $productData): Product
    {        
        $product = new Product();
        DataMapper::mapDataToObject($productData, $product);           
        return $product;
    }
}
