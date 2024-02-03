<?php

class Category extends Model
{

    /**
     * Product constructor.
     */
    function __construct()
    {
        $this->table_name = "category";
        $this->id_column = "category_id";
    }
	
	// Масив імен категорій
	public function getCategoriesNames() : array
	{
            return $this->getOneColumnArray('category_name');
	}

	// Масив id категорій
	public function getCategoriesIds() : array
	{
            return $this->getOneColumnArray('category_id');
	}
        
        // Масив id категорій
	public function getCategoriesDetails() : array
	{
            $categories = $this->initCollection()->getCollection()->select();
            return $categories;
	}
        
         // Масив категорій
	public function getCategories() : array
	{
            return array_combine($this->getCategoriesIds(), $this->getCategoriesNames());
        }
        
         // Масив id категорій
	public function getCategoryNameById(int $categoryId) : string
	{
            $categories = $this->initCollection()->getCollection()->select();
            return $categories[$categoryId]['category_name'];
	}
}