<?php

/**
 * Class Product
 */
class Product extends Model
{
    /**
     * Product constructor.
     */
    function __construct()
    {
        $this->table_name = "product";
        $this->id_column = "product_id";
    }
	
	public function initProductCollection(int $categoryId) : Product
    {        
		$this->sql = "SELECT * FROM $this->table_name INNER JOIN product_category ON product_category.product_id = $this->table_name.$this->id_column
		WHERE product_category.category_id = $categoryId";
		//echo $this->sql; exit;
        return $this;
    }
	
	
	//МЕТОД ДОДАВАННЯ НОВОГО ТОВАРУ    
    public function addProduct()
	{
		//збільшуємо id нового товару на одницю
		$product_id = $this->MaxValue($this->id_column) + 1;		
		
                //параметри введеного товару
		$this->params = array_merge([$product_id],$this->FormData());                                
		$this->addItem($this->getColumnsNames());                
		//якщо корректно введені всі поля - додати
		if (isset($_POST['add']) && !Helper::isEmpty('product') &&
		Helper::isNumericInput(['price','qty']))
		{
			$this->addItem($this->getColumnsNames());
			Helper::$var['message']="ok";
		}		
		return $this;
	}
	
	//МЕТОД ФІЛЬТРУВАННЯ
    public function filter($params): Product
    {
		$param = array_keys($params)[0];
		
		//мінімальне значення
		Helper::$var['min_price'] = $this->LowerPrice($param);

		//максимальне значення
		Helper::$var['max_price'] = $this->HigherPrice($param);
		
		//межі фільтрування 
		$max = $this -> HigherPrice($param);
		$min = $this -> LowerPrice($param);
		
		//формування частини sql-запиту "WHERE price BETWEEN"
		$this -> sql .= " AND $param BETWEEN ". $min." AND ". $max;		
		
		return $this;
	}
   
}