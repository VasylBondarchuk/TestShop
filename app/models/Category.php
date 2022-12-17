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
   
}