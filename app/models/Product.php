<?php

/**
 * Class Product
 */
class Product extends Model {

    /**
     * Product constructor.
     */
    function __construct() {
        $this->table_name = "product";
        $this->id_column = "product_id";
    }

    public function initProductCollection(int $categoryId): Product {
        $this->sql = "SELECT * FROM $this->table_name INNER JOIN product_category ON product_category.product_id = $this->table_name.$this->id_column
		WHERE product_category.category_id = $categoryId";
        return $this;
    }

    //МЕТОД ДОДАВАННЯ НОВОГО ТОВАРУ    
    public function addProduct() {
        //збільшуємо id нового товару на одницю
        $productId = $this->MaxValue($this->id_column) + 1;
        //параметри введеного товару
        $this->params = array_merge([$productId], $this->FormData());
        $this->addItem($this->getColumnsNames());
        //якщо корректно введені всі поля - додати
        if (isset($_POST['add']) && !Helper::isEmpty('product') &&
                Helper::isNumericInput(['price', 'qty'])) {
            $this->addItem($this->getColumnsNames());
            Helper::$var['message'] = "ok";
        }
        $this->addProductToCategory($productId, $_POST['category_id']);
        return $this;
    }   
    
     public function editProduct(int $productId, array $categoryIds) { 
        $data = $this->FormData();        
        if($_FILES['product_image']['name'] == ''){           
            $data[] = $this->getProductById($productId)['product_image'];            
        }        
        $this->editItem($productId, $data);  
        $this->deleteProductFromCategory($productId);
        $this->addProductToCategory($productId, $categoryIds);
        return $this;
    }    
    
    public function addProductToCategory(int $productId, array $categoryIds) {
        $db = new DB();
        $value = '';
        foreach ($categoryIds as $categoryId) {
            $value .= "($productId,$categoryId), ";
        }
        $productIdCategoryIdValues = rtrim($value, ", ");
        $sql = "INSERT INTO `product_category` (`product_id`, `category_id`)
        VALUES  $productIdCategoryIdValues;";
        $db->query($sql);
    }
    
        public function deleteProductFromCategory(int $productId) {
        $db = new DB();        
        $sql = "DELETE FROM `product_category` WHERE `product_id` = $productId";
        $db->query($sql);
    }

    public function deleteProduct(int $productId) {
        $db = new DB();
        $sql = "DELETE FROM product_category WHERE product_id = ?;";
        $db->query($sql, [$productId]);
        $this->deleteItem($productId);
    }

    //МЕТОД ФІЛЬТРУВАННЯ
    public function filterByPrice(): Product {
        $min = $this->getMinValue('price');
        $minIput = Helper::getFilteringInput('minPrice') ?: $min;

        $max = $this->getMaxValue('price');
        $maxIput = Helper::getFilteringInput('maxPrice') ?: $max;

        if ($minIput > $maxIput) {
            $minIput = $min;
            $maxIput = $max;
        }

        if ($minIput > $max) {
            $minIput = $max;
        }
        $this->filter('price', $minIput, $maxIput);
        return $this;
    }

    public function getProductById(int $productId) {
        return $this->getItem($productId);
    }
    
    public function getProductCategories(int $productId) : array {
        $db = new DB();
        $sql = "SELECT `category_id` FROM `product_category` WHERE `product_id` = $productId;";
        $result = $db->query($sql);
        $categoryIds = [];
        foreach($result as $category){
            $categoryIds[] = $category['category_id'];
        }
        return $categoryIds;
    }
}
