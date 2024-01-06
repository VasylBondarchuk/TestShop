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
        $product_id = $this->MaxValue($this->id_column) + 1;

        //параметри введеного товару
        $this->params = array_merge([$product_id], $this->FormData());
        $this->addItem($this->getColumnsNames());
        //якщо корректно введені всі поля - додати
        if (isset($_POST['add']) && !Helper::isEmpty('product') &&
                Helper::isNumericInput(['price', 'qty'])) {
            $this->addItem($this->getColumnsNames());
            Helper::$var['message'] = "ok";
        }
        return $this;
    }

    public function deleteProduct() {
        //параметри введеного товару
        $this->params = array_merge([$product_id], $this->FormData());
        $this->addItem($this->getColumnsNames());
        //якщо корректно введені всі поля - додати
        if (isset($_POST['add']) && !Helper::isEmpty('product') &&
                Helper::isNumericInput(['price', 'qty'])) {
            $this->addItem($this->getColumnsNames());
            Helper::$var['message'] = "ok";
        }
        return $this;
    }

    //МЕТОД ФІЛЬТРУВАННЯ
    public function filterByPrice() : Product {
        $min = $this->getMinValue('price');
        $minIput = Helper::getFilteringInput('minPrice') ? : $min;

        $max = $this->getMaxValue('price');
        $maxIput = Helper::getFilteringInput('maxPrice') ? : $max;

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
}
