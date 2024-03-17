<?php
namespace app\modules\product\Model;

use app\core\ResourceModel;
use app\modules\product\Model\Product;

class ProductResourceModel extends ResourceModel
{
    public function __construct()
    {
        // Specify the table name for the Product model
        parent::__construct('product');
    }

    public function getProductCollection() : array
    {
        // Fetch all products using the fetchAll() method from the parent ResourceModel class
        $productsData = $this->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setProductId($productData[Product::PRODUCT_ID]);
            $product->setSku($productData[Product::SKU]);
            $product->setName($productData[Product::NAME]);
            $product->setPrice($productData[Product::PRICE]);
            $product->setQty($productData[Product::QTY]);
            $product->setDescription($productData[Product::DESCRIPTION]);
            $product->setProductImage($productData[Product::PRODUCT_IMAGE]);
            $products[] = $product;
        }
        return $products;
    }
}
