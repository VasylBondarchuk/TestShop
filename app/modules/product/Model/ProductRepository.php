<?php

namespace app\modules\product\Model;

use app\modules\product\Model\Product;
use app\modules\product\Model\ProductResourceModel;
use app\core\Logger;

class ProductRepository {

    private ProductResourceModel $productResourceModel;
    private Logger $logger;

    public function __construct(
            ProductResourceModel $productResourceModel,
            Logger $logger) {
        $this->productResourceModel = $productResourceModel;
        $this->logger = $logger;
    }

    public function getById(int $productId): ?Product {        
        try {
            $product = $this->productResourceModel->fetchProductById($productId);
        } catch (\Exception $e) {            
            $this->logger->log("Error fetching product: " . $e->getMessage());
            $product = null;
        }
        return $product;
    }

    public function getAll(): array {
        $productDataCollection = $this->productResourceModel->fetchAll();
        $products = [];
        foreach ($productDataCollection as $productData) {
            $products[] = $this->productResourceModel->mapProductDataToModel($productData);
        }
        return $products;
    }    
}
