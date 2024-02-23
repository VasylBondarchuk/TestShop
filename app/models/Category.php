<?php

class Category extends Model {

    private int $categoryId;
    private string $categoryName;
    private int $categoryActive;
    private array $products = [];

    /**
     * Category constructor.
     */
    public function __construct() {
        $this->table_name = "category";
        $this->id_column = "category_id";
    }

    /**
     * Retrieve a collection of categories.
     * 
     * @return array Array of Category objects.
     */
    public function getCollection(): array {
        $db = new DB();
        $categoriesData = $db->query("SELECT * FROM $this->table_name");

        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $category = new Category();
            $category->setCategoryId($categoryData['category_id']);
            $category->setCategoryName($categoryData['category_name']);
            $category->setCategoryActive($categoryData['category_active']);
            // Set other properties if needed
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Set the category ID.
     * 
     * @param int $categoryId The category ID.
     */
    public function setCategoryId(int $categoryId): void {
        $this->categoryId = $categoryId;
    }

    /**
     * Get the category ID.
     * 
     * @return int The category ID.
     */
    public function getCategoryId(): int {
        return $this->categoryId;
    }

    /**
     * Set the category name.
     * 
     * @param string $categoryName The category name.
     */
    public function setCategoryName(string $categoryName): void {
        $this->categoryName = $categoryName;
    }

    /**
     * Get the category name.
     * 
     * @return string The category name.
     */
    public function getCategoryName(): string {
        return $this->categoryName;
    }

    /**
     * Set the category active status.
     * 
     * @param int $categoryActive The category active status.
     */
    public function setCategoryActive(int $categoryActive): void {
        $this->categoryActive = $categoryActive;
    }

    /**
     * Get the category active status.
     * 
     * @return int The category active status.
     */
    public function getCategoryActive(): int {
        return $this->categoryActive;
    }

    /**
     * Set the products assigned to the category.
     * 
     * @param array $products An array of Product objects.
     */
    public function setProducts(array $products): void {
        $this->products = $products;
    }

    /**
     * Get the products assigned to the category.
     * 
     * @return array An array of Product objects.
     */
    public function getProducts(): array {
        return $this->products;
    }

    /**
     * Retrieve the names of all categories.
     * 
     * @return array An array of category names.
     */
    public function getCategoriesNames(): array {
        $db = new DB();
        $categoriesData = $db->query("SELECT category_name FROM $this->table_name");

        return array_column($categoriesData, 'category_name');
    }

    // Масив id категорій
    public function getCategoriesIds(): array {
        $db = new DB();
        $categoriesData = $db->query("SELECT category_id FROM $this->table_name");

        return array_column($categoriesData, 'category_id');
    }

    // Масив категорій
    public function getCategories(): array {        
        return array_combine($this->getCategoriesIds(), $this->getCategoriesNames());
    }    

    // Implement setters and getters for other properties similarly
}
