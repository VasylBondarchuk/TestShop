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
            return $product->isProductInCategory($product->getProductId(), $categoryId);
        });

        return $productsInCategory;
    }    

    public function removeFromAllCategories(int $productId): void {
        // You need to implement the logic to remove the product from all categories here

        $db = new DB();
        $sql = "DELETE FROM product_category WHERE product_id = ?";
        $db->query($sql, [$productId]);
    }
    
    public function addProduct() {
        $columnNames = $this->getColumnsNames();
        $data = Helper::getFormData($columnNames);        
        array_shift($columnNames);
        $this->addItem($columnNames, $data);        
    }

    // Separate method to handle file upload and update data array
    private function handleProductImage(int $productId, array &$data): void {
        // Get filtered data from file upload
        $filteredData = Helper::handleFileUpload();

        // Check if a new image file has been uploaded
        if (!empty($filteredData[self::PRODUCT_IMAGE])) {
            // If a file was uploaded, update the data array with the filename
            $data[self::PRODUCT_IMAGE] = $filteredData[self::PRODUCT_IMAGE];
        } else {
            // If no new file was uploaded, retain the existing product image in the data array
            $existingProduct = $this->getProductById($productId);
            if ($existingProduct) {
                $data[self::PRODUCT_IMAGE] = $existingProduct->getProductImage();
            }
        }
    }

// Separate method to retrieve all form data including photo data
    private function getEditFormData(int $productId): array {
        $data = Helper::getFormData($this->getColumnsNames());

        // Handle product image
        $this->handleProductImage($productId, $data);

        return $data;
    }

    public function editProduct(int $productId, array $categoryIds): Product {
        // Get all form data including photo data
        $data = $this->getEditFormData($productId);

        // Check if the SKU is unique for this product
        $newSku = $data['sku'] ?? ''; // Get the new SKU from the form data
        if (!$this->isSkuUniqueForProduct($newSku, $productId)) {
            throw new Exception('SKU must be unique.');
        }

        // Perform the rest of the product editing logic
        $this->editItem($productId, $data);

        // Update product-category associations
        $this->updateProductCategories($productId, $categoryIds);

        return $this;
    }

    private function isSkuUniqueForProduct(string $sku, int $productId): bool {
        $db = new DB();
        $sql = "SELECT COUNT(*) AS count FROM $this->table_name WHERE sku = ? AND $this->id_column != ?";
        $params = [$sku, $productId];
        $result = $db->query($sql, $params);

        // Check if any product other than the specified one has the same SKU
        return $result['count'] == 0;
    }
    
    public function addToCategory(int $productId, int $categoryId): void {
        // You need to implement the logic to add the product to the specified category here

        $db = new DB();
        $sql = "INSERT INTO product_category (product_id, category_id) VALUES (?, ?)";
        $db->query($sql, [$productId, $categoryId]);
    }

    public function updateProductCategories(int $productId, array $categoryIds): void {
        // Get the product instance
        $product = $this->getProductById($productId);

        // Remove the product from all categories first
        $product->removeFromAllCategories($productId);

        // Assign the product to the specified categories
        foreach ($categoryIds as $categoryId) {
            $product->addToCategory($productId, $categoryId);
        }
    }

    public function assignProductToCategories(int $productId, array $categoryIds): void {
        // Get the product instance
        $product = $this->getProductById($productId);

        // Assign the product to the specified categories
        foreach ($categoryIds as $categoryId) {
            $product->addToCategory($productId, $categoryId);
        }
    }

    public function unassignProductFromAllCategories(int $productId): void {
        // Get the product instance
        $product = $this->getProductById($productId);

        // Remove the product from all categories
        $product->removeFromAllCategories();
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
            foreach ($this->getColumnsNames() as $columnName) {
                // Remove underscores and capitalize words
                $formattedColumnName = str_replace('_', '', ucwords($columnName, '_'));
                $setterMethod = 'set' . $formattedColumnName;
                if (method_exists($product, $setterMethod)) {
                    $product->$setterMethod($productData[$columnName]);
                }
            }
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
            MessageManager::setError("Product ID and category ID must be positive integers.");           
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


/**
     * Retrieve a paginated collection of products.
     * 
     * @param int $perPage Number of products per page.
     * @param int $currentPage Current page number.
     * @return array Paginated collection of Product objects.
     */
    public function getPaginatedCollection(int $perPage, int $currentPage): array {
        $db = new DB();

        // Calculate the offset based on current page and number of products per page
        $offset = ($currentPage - 1) * $perPage;

        // Fetch products with LIMIT and OFFSET for pagination
        $query = "SELECT * FROM $this->table_name LIMIT $perPage OFFSET $offset";
        $productsData = $db->query($query);

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

    public function getTotalProducts(): int {
    $db = new DB();
    $result = $db->query("SELECT COUNT(*) AS total FROM $this->table_name");
    
    // Check if the result is not empty
    if (!empty($result['total'])) {
        return (int) $result['total'];
    } else {
        // If no products found, return 0
        return 0;
    }
}

}
