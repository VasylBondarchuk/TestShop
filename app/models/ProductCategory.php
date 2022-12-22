<?php

/**
 * Class ProductCategory
 */
class ProductCategory extends Model
{
    /**
     * Product constructor.
     */
    function __construct()
    {
        $this->table_name = "product_category";
        $this->id_column = "product_id";
    }

//МЕТОД ДОДАВАННЯ НОВОГО ТОВАРУ
    public function assignProductToCategory(int $productId): ProductCategory
    {
        $categoryId = (int)$_POST['category_id'];
        //параметри введеного товару
        $params = [$productId,$categoryId];
        $this->addItem($this->getColumnsNames(), $params);
        return $this;
    }
}