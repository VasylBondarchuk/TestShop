<?php

/**
 * Class Model
 */
class Model
{  
	// ім'я таблиці БД
    protected $table_name;
    
    // назва id-колонки таблиці
    protected $id_column;
    
    // масив імен колонок таблиці БД
    protected $columns = [];
    
    protected $collection;
    
    // рядок sql-запита
    protected $sql;
    
    protected $params = [];
	
	// Метод отримання назва id-колонки таблиці
	public function getIdColumn()
	{
		return $this->id_column;
	}

	// АБСТРАКТНИЙ МЕТОД МЕТОД ДОДАВАННЯ ЗАПИСУ ДО ТАБЛИЦІ table_name БД
	public function addItem($columns, $params)
	{			
		$db = new DB();		
		//отримання рядку "(?,?,?,?......,?,?)"
		$questionMarks = trim(str_repeat('?,',count($columns)),',');		
		$sql = "INSERT INTO {$this->table_name} VALUES ($questionMarks);";		
		return $db->query($sql, $params);
	}
	
	//МЕТОД СОРТУВАННЯ
	public function sort($params)
    {	
		//формування частини sql-запиту "ORDER BY колонка тип сортування"
		$this->sql .= " ORDER BY ";

		foreach($params as $sortParam => $sortOrder)
		{
			$this->sql .= $sortParam .' '. $sortOrder . ',';
		}
		$this->sql = rtrim($this->sql,',');						
			
		return $this;		
	}

	//МЕТОД ФІЛЬТРУВАННЯ
    public function filter($params)
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


	// АБСТРАКТНИЙ МЕТОД МЕТОД РЕДАГУВАННЯ ЗАПИСУ ТАБЛИЦІ table_name БД
    public function editItem(int $id): Model
    {
		//данні введення форми редагування
		$db = new DB();
		$q = [];
		//формування частини запиту зі знаками питання
		foreach($this->getColumnsNames() as &$column){
			$q[] = "$column = ?"; 
		}
		$q_marks = implode(',',$q);
		$sql = "UPDATE {$this->table_name} SET $q_marks WHERE {$this->id_column}=?;";
        $params =  array_merge([$id],$_POST,[$id]);
        $db->query($sql, array_values($params));
	return $this;
	}

	//отримання значень форми
	public function FormData(): array
    {
		// імена колонок
		$columns = $this->getColumnsNames();
		
		// масив для отримання данних з форми
		$formData = [];

		// ітерація по масиву отриманих з форми данних
		foreach($_POST as $key => $value)
		{
			// записуємо тільки ті, для яких є редагована колонка в БД
			if(in_array($key, $columns)){
				$formData[] = $value;
			}	 		
		}		
		return $formData;
	}

	// АБСТРАКТНИЙ МЕТОД ВИДАЛЕННЯ ЗАПИСУ З ТАБЛИЦІ table_name БД
	public function deleteItem($id): Model
    {
		if (isset($_POST['Delete']))
		{
			$db  = new DB();
			$sql = "DELETE FROM {$this->table_name} WHERE {$this->id_column} = ?;";
			$results = $db->query($sql, [$id]);			
		}
		return $this;
	}

	
	public function initCollection(): Model
    {
        $this->sql = "SELECT * FROM " . $this->table_name ;
		return $this;
    }	

    // Отримання масиву значень колонки $column_name таблиці $table_name БД
    public function getColumnArray($column_name): array
    {
        $db = new DB();
        $sql = "SELECT {$column_name} FROM {$this->table_name};";
        $results = $db->query($sql);        
        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach($results as $result => $value)
        {
            $column_values_array[] = $value[$column_name];
        }
	    return $column_values_array;
	}

    // Перевірка чи введенне значення є унікальним (не належить масиву існуючих значень, крім вже введеного)
    public function IsValueExists($check, $column_name): bool
    {
        $db = new DB();
        $sql = "SELECT {$column_name} from {$this->table_name};";
        $results = $db->query($sql);        
        $column_values_array = [];
        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach($results as $result => $value)
        {            
            $column_values_array[] = $value[$column_name];
        }	    
	    return in_array($check, $column_values_array);
	}

   	public function getColumnsNames(): array
    {
		$db = new DB();
		$sql = "SELECT * FROM $this->table_name;";
		$results = $db->query($sql);
		return array_keys($results[0]);
	}
	
	// отримання запису з бази за конкретним значенням певної колонки таблиці table_name БД
    public function getItemByParam($column,$value)
    {
        $db  = new DB();
		$sql = "SELECT * FROM $this->table_name WHERE $column=?";
		$params = [$value];
		return empty($db->query($sql, $params )[0])? array():
		$db->query($sql, $params)[0];
    }
	
	// Метод отримання макс. значення конкретної колонки таблиці table_name БД
	public function MaxValue($column)
	{
		$db = new DB();
		$sql = "SELECT MAX($column)FROM {$this->table_name};"; 
		$results = $db->query($sql);
	 	return $results[0]["MAX($column)"];
	}

	// Метод отримання макс. значення конкретної колонки таблиці table_name БД
	public function MinValue($column)
	{
		$db = new DB();
		$sql = "SELECT MIN($column)FROM {$this->table_name};"; 
		$results = $db->query($sql);
	 	return floatval($results[0]["MIN($column)"]);
	}

	//отримання верхнього значення введеного параметра
	public function HigherPrice($param)
	{
		//максимально можливе значення параметру
		$max = $this->MaxValue($param);
		
		if(isset($_POST[$param][1])){//отримання верхнього значення сортування
			if (!empty($_POST[$param][1])){
				$max = floatval($_POST[$param][1])>= $max
				? $max
				: floatval($_POST[$param][1]);
			}	
		}	
		return 100;
	}

	//отримання нижнього значення введеного параметра
	public function LowerPrice($param)
	{
		//мінімально можливе значення параметру
		$min = 0;				
	  	if(isset($_POST[$param][0])){//отримання нижнього значення сортування
			if (!empty($_POST[$param][0])) {
				$min = floatval($_POST[$param][0])>=$this->HigherPrice($param)? $min:floatval($_POST[$param][0]);
			}	
		}
		return $min;
	}
	
	public function getCollection(): Model
    {
        $db = new DB();
        $this->sql .= ";";
        $this->collection = $db->query($this->sql, $this->params);		
        return $this;
    }

	//метод перевірки порожніх введеннь
	public function isEmpty(array $params)
	{
		foreach ($params as $element){
			if ($element=='')return FALSE;
		}
		return TRUE;	
	}

    public function select()
    {
        return $this->collection;
    }
    
    public function selectFirst()
    {
        return $this->collection[0] ?? null;
    }

    // Метод отримання данних рядка таблиці table_name за id
    public function getItem(int $id)
    {
        $sql = "SELECT * FROM {$this->table_name} WHERE {$this->id_column} = ?;";
        $db = new DB();
        $results = $db->query($sql , [$id]);
        // Повернути масив, що містить данні  
        return $results[0];
    }

    // Метод отримання данних рядка таблиці table_name за id
    public function getItemUniv($column,$value)
    {
        $this->sql = "SELECT * FROM {$this->table_name} WHERE $column = ?;";        
        $db = new DB();
        $results =$db->query($this->sql,array($value));
        // Повернути масив, що містить данні  
        return $this;
    }

    public function getPostValues()
    {
        $values = [];
        $columns = $this->getColumnsNames();
        foreach ($columns as $column) {
            $column_value = filter_input(INPUT_POST, $column);
            if ($column_value && $column !== $this->id_column ) {
                $values[$column] = $column_value;
            }
        }        
        return $values;
    }

    // Метод отримання імені колонки з id
    public function getIdName()
    {
        return $this->id_column;
    }

    // Отримання масиву значень колонки $column_name таблиці $table_name БД
    public function getOneColumnArray($column_name)
    {
        $db = new DB();
        $sql = "select {$column_name} from {$this->table_name};";
        $results = $db->query($sql);
        
        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach($results as $result => $value)
        {
            $column_values_array[] = $value[$column_name];
        }
	    return $column_values_array;
	}

	public function IsEditProductFormInputCorrect(): bool
    {
			$params=array_merge(['id'],$this->FormData());
			
			// Введений sku 
        	$entered_sku = Helper::ClearInput($_POST['sku']);  	
        				
				switch (FALSE)
				{
                    case !$this->isEmpty($params):
                    case (Helper::isNumericInput(array($_POST['price'], $_POST['qty']))):
                    case ($entered_sku == $this->getItem(Helper::getId())['sku']
				    || !($this->IsValueExists($entered_sku,"sku"))):
				    return FALSE;break;

                    default: return TRUE;
				}
	}

	// Отримання імен редагованих колонок
	public function getEditableColumn(): array
    {
		//імена колонок
		$columns = $this->getColumnsNames();		
		//імена колонок, що редагуються
        return array_intersect($columns,array_keys($_POST));
	}

	// Перевірка, чи всі введення у формі клрректні

	public function AllInputsCorrects()
	{
		// Отримання імен редагованих колонок
		$columns = $this->getEditableColumn();
		
		// Перевірка правильності введення у кожній колнці
		foreach($columns as $column)
		{
			return Helper::FormIcorrectInputMessage($column);
		}
		return TRUE;
	}
}