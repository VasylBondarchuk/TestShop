<?php
namespace app\modules\category\Model;

use app\core\ResourceModel;
use app\modules\category\Model\Category;
use app\core\DataMapper;

class CategoryResourceModel extends ResourceModel
{   
    public function __construct()
    {        
        // Specify the table name for the Product model
        parent::__construct(Category::TABLE_NAME, Category::CATEGORY_ID);
    }

    public function getCategoryCollection() : array
    {
        // Fetch all products using the fetchAll() method from the parent ResourceModel class
        $categoriesData = $this->fetchAll();
        
        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $category = $this->mapCategoryDataToModel($categoryData);
            $categories[] = $category;
        }
        return $categories;
    }
    
    public function fetchProductById(int $productId): ?Product
    {
        $productData = $this->fetchById($productId);
        if (!$productData) {
            return null;
        }        
        return $this->mapProductDataToModel($productData);
    }

    private function mapCategoryDataToModel(array $categoryData): Category
    {        
        $category = new Category();
        DataMapper::mapDataToObject($categoryData, $category);           
        return $category;
    }
}
