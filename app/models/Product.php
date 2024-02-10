<?php

/**
 * Class Product
 */
class Product extends Model {

    const PRODUCT_ID = 'product_id';
    const SKU = 'sku';
    const NAME = 'name';
    const PRICE = 'price';
    const QTY = 'qty';
    const DESCRIPTION = 'description';
    const PRODUCT_IMAGE = 'product_image';

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
        $this->params = $this->FormData();
        $columnNames = $this->getColumnsNames();
        array_shift($columnNames);
        $this->addItem($columnNames);
        $this->addProductToCategory($this->getLastId(), $_POST['category_id']);
    }

    public function editProduct(int $productId, array $categoryIds): Product {
        $data = $this->FormData();
        if ($_FILES['product_image']['name'] == '') {
            $data[] = $this->getProductById($productId)['product_image'];
        }
        $this->editItem($productId, $data);
        $this->deleteProductFromCategory($productId);
        $this->addProductToCategory($productId, $categoryIds);
        return $this;
    }

    public function addProductToCategory($productId, array $categoryIds) {
        $db = new DB();
        $value = '';
        foreach ($categoryIds as $categoryId) {
            $value .= "($productId,$categoryId), ";
        }
        $productIdCategoryIdValues = rtrim($value, ", ");
        $sql = "INSERT INTO product_category (" . self::PRODUCT_ID . ", category_id)
        VALUES  $productIdCategoryIdValues";
        $db->query($sql);
    }

    public function deleteProductFromCategory(int $productId) {
        $db = new DB();
        $sql = "DELETE FROM product_category WHERE " . self::PRODUCT_ID . " = $productId";
        $db->query($sql);
    }

    public function deleteProduct(int $productId) {        
        $this->deleteItem($productId);
    }

    //МЕТОД ФІЛЬТРУВАННЯ
    public function filterByPrice(): Product {
        $minPrice = $this->getMinValue(self::PRICE);
        $minPriceIput = is_numeric(Helper::getFilteringInput('minPrice')) ? Helper::getFilteringInput('minPrice') : $minPrice;

        $maxPrice = $this->getMaxValue(self::PRICE);
        $maxPriceIput = is_numeric(Helper::getFilteringInput('maxPrice')) ? Helper::getFilteringInput('maxPrice') : $maxPrice;

        if ($minPriceIput > $maxPriceIput) {
            $minPriceIput = $minPrice;
            $maxPriceIput = $maxPrice;
        }

        if ($minPriceIput > $maxPrice) {
            $minPriceIput = $minPrice;
        }
        $this->filter(self::PRICE, $minPriceIput, $maxPriceIput);
        return $this;
    }

    public function getProductById(int $productId) {
        return $this->getItem($productId);
    }

    public function getProductBySku(string $productSku) {
        return $this->getItem($productSku);
    }

    public function getProductCategories(int $productId): array {
        $db = new DB();
        $sql = "SELECT category_id FROM product_category WHERE " . self::PRODUCT_ID . " = $productId";
        $result = $db->query($sql);
        $categoryIds = [];
        foreach ($result as $category) {
            $categoryIds[] = $category['category_id'];
        }
        return $categoryIds;
    }

    public function isProductInCategory(int $productId, int $categoryId): bool {
        $categoryIds = $this->getProductCategories($productId);
        return in_array($categoryId, $categoryIds);
    }
    
    public function getProductName(int $productId): string {
        return $this->getProductById($productId)[self::NAME];
    }
}