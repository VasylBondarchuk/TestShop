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

    protected int $productId;
    protected string $sku;
    protected string $name;
    protected float $price;
    protected int $qty;
    protected string $description;
    protected string $productImage;

    function __construct() {
        $this->table_name = 'product';
        $this->id_column = 'product_id';
    }

    public function setProductId(int $productId): void {
        $this->productId = $productId;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function setSku(string $sku): void {
        $this->sku = $sku;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setQty(int $qty): void {
        $this->qty = $qty;
    }

    public function getQty(): int {
        return $this->qty;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setProductImage(string $productImage): void {
        $this->productImage = $productImage;
    }

    public function getProductImage(): string {
        return $this->productImage;
    }

    /**
     * Retrieve a collection of products.
     * 
     * @return array Collection of Product objects.
     */
    public function getCollection(): array {
        $db = new DB();
        $productsData = $db->query("SELECT * FROM $this->table_name");

        $products = [];
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setProductId($productData[self::PRODUCT_ID]);
            $product->setSku($productData[self::SKU]);
            $product->setName($productData[self::NAME]);
            $product->setPrice($productData[self::PRICE]);
            $product->setQty($productData[self::QTY]);
            $product->setDescription($productData[self::DESCRIPTION]);
            $product->setProductImage($productData[self::PRODUCT_IMAGE]);
            $products[] = $product;
        }

        return $products;
    }

    /**
     * Retrieve a collection of products assigned to a certain category.
     * 
     * @param int $categoryId The ID of the category.
     * @return array Collection of Product objects assigned to the specified category.
     */
    public function getProductsByCategory(int $categoryId): array {
        // Call the existing getCollection() method to fetch all products
        $products = $this->getCollection();

        // Filter the products to retain only those assigned to the specified category
        $productsInCategory = array_filter($products, function ($product) use ($categoryId) {
            return $product->isProductInCategory($product->getProductId(),$categoryId);
        });

        return $productsInCategory;
    }    

    //МЕТОД ДОДАВАННЯ НОВОГО ТОВАРУ    
    public function addProduct() {
        $columnNames = $this->getColumnsNames();
        $this->params = Helper::getFormData($columnNames);
        array_shift($columnNames);
        $this->addItem($columnNames);
        $this->addProductToCategory($this->getLastId(), $_POST['category_id']);
    }

    public function editProduct(int $productId, array $categoryIds): Product {
        $data = Helper::getFormData($this->getColumnsNames());
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

    /**
     * Filter products by price within a specified range.
     * 
     * @param array $products The array of Product objects to filter.
     * @param float|null $minPrice The minimum price to filter by.
     * @param float|null $maxPrice The maximum price to filter by.
     * @return array The filtered array of Product objects.
     */
    public function filterProductsByPrice(array $products, ?float $minPrice, ?float $maxPrice): array {
        // Validate input parameters
        if ($minPrice !== null && $maxPrice !== null && $minPrice > $maxPrice) {
            // If minimum price is greater than maximum price, return empty array
            return [];
        }

        // Initialize an array to store filtered products
        $filteredProducts = [];

        // Iterate over each product in the array
        foreach ($products as $product) {
            // Get the price of the current product
            $productPrice = $product->getPrice();

            // Check if the product's price falls within the specified range
            if (($minPrice === null || $productPrice >= $minPrice) && ($maxPrice === null || $productPrice <= $maxPrice)) {
                // Add the product to the filtered array
                $filteredProducts[] = $product;
            }
        }

        // Return the filtered array of products
        return $filteredProducts;
    }

    /**
     * Retrieve a product by its ID
     * 
     * @param int $productId The ID of the product to retrieve
     * @return Product|null The product object if found, or null if not found
     */
    public function getProductById(int $productId): ?Product {
        $productData = $this->getItem($productId);

        if ($productData) {
            $product = new Product();
            $product->setProductId($productData[self::PRODUCT_ID]);
            $product->setSku($productData[self::SKU]);
            $product->setName($productData[self::NAME]);
            $product->setPrice($productData[self::PRICE]);
            $product->setQty($productData[self::QTY]);
            $product->setDescription($productData[self::DESCRIPTION]);
            $product->setProductImage($productData[self::PRODUCT_IMAGE]);
            return $product;
        } else {
            return null;
        }
    }

    public function getProductBySku(string $productSku) {
        return $this->getItem($productSku);
    }

    public function getProductCategories(int $productId): array {
        $db = new DB();
        $sql = "SELECT category_id FROM product_category WHERE " . self::PRODUCT_ID . " = ?";
        $result = $db->query($sql, [$productId]);
        $categoryIds = [];
        foreach ($result as $category) {
            $categoryIds[] = $category['category_id'];
        }
        return $categoryIds;
    }

    public function isProductInCategory(int $productId, int $categoryId): bool {
        // Validate input parameters
        if ($productId <= 0 || $categoryId <= 0) {
            throw new InvalidArgumentException("Product ID and category ID must be positive integers.");
        }

        // Get category IDs associated with the product
        $categoryIds = $this->getProductCategories($productId);

        // Check if the specified category ID exists in the array of category IDs
        if ($categoryIds === false) {
            // Handle error fetching category IDs
            throw new RuntimeException("Failed to fetch category IDs for product.");
        }

        return in_array($categoryId, $categoryIds);
    }    

    public function findCollectionMinMaxPropertyValue(array $collection, string $property, string $flag = 'min') {
        // If no items found, return null
        if (empty($collection)) {
            return null;
        }

        // Initialize min/max value
        $result = null;

        // Iterate over collection to find the minimum or maximum value of the property
        foreach ($collection as $item) {
            $value = $item->{$property}; // Access property directly without calling a method

            if ($flag === 'min') {
                // Find the minimum value
                if ($result === null || $value < $result) {
                    $result = $value;
                }
            } elseif ($flag === 'max') {
                // Find the maximum value
                if ($result === null || $value > $result) {
                    $result = $value;
                }
            } else {
                // Invalid flag value
                throw new InvalidArgumentException("Invalid flag value. Expected 'min' or 'max'.");
            }
        }

        return $result;
    }

    /**
     * Sorts an array of Product objects by price.
     *
     * @param array  $products The array of Product objects to be sorted.
     * @param string $order    The order of sorting ('asc' for ascending, 'desc' for descending).
     *
     * @return array The sorted array of Product objects.
     */
    public function sortProductsByPrice(array $products, ?string $order = 'asc'): array {
        return $this->sortCollectionByProperty($products, 'price', $order);
    }   
     
}
