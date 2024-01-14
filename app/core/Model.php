<?php

// Enable error reporting
error_reporting(E_ALL);
// Display errors on the screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/**
 * Class Model
 */
class Model {

    // ім'я таблиці БД
    protected string $table_name;
    // назва id-колонки таблиці
    protected string $id_column;
    // масив імен колонок таблиці БД
    protected array $columns = [];
    protected array $collection;
    // рядок sql-запита
    protected $sql;
    protected $params = [];

    // Метод отримання назва id-колонки таблиці
    public function getIdColumn() {
        try {
            $idColumn = $this->id_column;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $idColumn;
    }

    // АБСТРАКТНИЙ МЕТОД МЕТОД ДОДАВАННЯ ЗАПИСУ ДО ТАБЛИЦІ table_name БД
    public function addItem($columns) {
        $db = new DB();
        //отримання рядку "(?,?,?,?......,?,?)"
        $questionMarks = trim(str_repeat('?,', count($columns)), ',');
        $sql = "INSERT INTO {$this->table_name} VALUES ($questionMarks);";
        return $db->query($sql, $this->params);
    }

    //МЕТОД СОРТУВАННЯ
    public function sort($params) {
        //формування частини sql-запиту "ORDER BY колонка тип сортування"
        $this->sql .= " ORDER BY ";

        foreach ($params as $sortParam => $sortOrder) {
            $this->sql .= $sortParam . ' ' . $sortOrder . ',';
        }
        $this->sql = rtrim($this->sql, ',');

        return $this;
    }

    //МЕТОД ФІЛЬТРУВАННЯ
     public function filter($column, $min, $max) {        
      //формування частини sql-запиту "WHERE price BETWEEN"
      $this->sql .= " AND $column BETWEEN " . $min . " AND " . $max;
      return $this;
      } 

    // АБСТРАКТНИЙ МЕТОД МЕТОД РЕДАГУВАННЯ ЗАПИСУ ТАБЛИЦІ table_name БД
    public function editItem(int $id, array $data): Model {
        $db = new DB();
        $q = [];
        $editableColumns = $this->getColumnsNames();
        array_shift($editableColumns);
        foreach ($editableColumns as &$column) {
            $q[] = "$column = ?";
        }
        $qMarks = implode(',', $q);
        $sql = "UPDATE {$this->table_name} SET $qMarks WHERE {$this->id_column}=?;";        
        $params = array_merge($data, [$id]);        
        $db->query($sql, $params);
        return $this;
    }

    //отримання значень форми
    public function FormData(): array {
        // імена колонок
        $columns = $this->getColumnsNames();
        
        // масив для отримання данних з форми
        $formData = [];

        // ітерація по масиву отриманих з форми данних
        foreach ($_POST as $key => $value) {
            // записуємо тільки ті, для яких є редагована колонка в БД
            if (in_array($key, $columns)) {
                $formData[] = $value;
            }
        }
        //echo $_FILES['product_image']['name'];exit;
        if ($_FILES['product_image']['name'] != '') {
            $formData[] = $_FILES['product_image']['name'];
        }
        return $formData;
    }

    // АБСТРАКТНИЙ МЕТОД ВИДАЛЕННЯ ЗАПИСУ З ТАБЛИЦІ table_name БД
    public function deleteItem(int $id): Model {        
            $db = new DB();
            $sql = "DELETE FROM {$this->table_name} WHERE {$this->id_column} = ?;";
            $db->query($sql, [$id]);        
        return $this;
    }

    public function initCollection(): Model {
        $this->sql = "SELECT * FROM " . $this->table_name;
        return $this;
    }

    // Отримання масиву значень колонки $column_name таблиці $table_name БД
    public function getColumnArray($column_name): array {
        $db = new DB();
        $sql = "SELECT {$column_name} FROM {$this->table_name};";
        $results = $db->query($sql);
        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach ($results as $result => $value) {
            $column_values_array[] = $value[$column_name];
        }
        return $column_values_array;
    }

    // Перевірка чи введенне значення є унікальним (не належить масиву існуючих значень, крім вже введеного)
    public function IsValueExists($check, $column_name): bool {
        $db = new DB();
        $sql = "SELECT {$column_name} from {$this->table_name};";
        $results = $db->query($sql);
        $column_values_array = [];
        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach ($results as $result => $value) {
            $column_values_array[] = $value[$column_name];
        }
        return in_array($check, $column_values_array);
    }

    public function getColumnsNames(): array {
        $db = new DB();
        $sql = "DESCRIBE $this->table_name";
        $results = $db->query($sql);
        return array_column($results, 'Field');
    }

    // отримання запису з бази за конкретним значенням певної колонки таблиці table_name БД
    public function getItemByParam($column, $value) {
        $db = new DB();
        $sql = "SELECT * FROM $this->table_name WHERE $column=?";
        $params = [$value];
        return empty($db->query($sql, $params)[0]) ? array() :
                $db->query($sql, $params)[0];
    }

    // Метод отримання макс. значення конкретної колонки таблиці table_name БД
    public function MaxValue($column) {
        $db = new DB();
        $sql = "SELECT MAX($column)FROM {$this->table_name};";
        $results = $db->query($sql);
        return $results[0]["MAX($column)"];
    }

    // Метод отримання макс. значення конкретної колонки таблиці table_name БД
    public function MinValue($column) {
        $db = new DB();
        $sql = "SELECT MIN($column)FROM {$this->table_name};";
        $results = $db->query($sql);
        return floatval($results[0]["MIN($column)"]);
    }

    //отримання верхнього значення введеного параметра
    public function HigherPrice($param) {
        //максимально можливе значення параметру
        $max = $this->MaxValue($param);

        if (isset($_POST[$param][1])) {//отримання верхнього значення сортування
            if (!empty($_POST[$param][1])) {
                $max = floatval($_POST[$param][1]) >= $max ? $max : floatval($_POST[$param][1]);
            }
        }
        return 100;
    }

    //отримання нижнього значення введеного параметра
    public function LowerPrice($param) {
        //мінімально можливе значення параметру
        $min = 0;
        if (isset($_POST[$param][0])) {//отримання нижнього значення сортування
            if (!empty($_POST[$param][0])) {
                $min = floatval($_POST[$param][0]) >= $this->HigherPrice($param) ? $min : floatval($_POST[$param][0]);
            }
        }
        return $min;
    }

    public function getCollection(): Model {
        $db = new DB();
        $this->sql .= ";";
        $this->collection = $db->query($this->sql, $this->params);
        return $this;
    }

    //метод перевірки порожніх введеннь
    public function isEmpty(array $params) {
        foreach ($params as $element) {
            if ($element == '')
                return FALSE;
        }
        return TRUE;
    }

    public function select() {
        return $this->collection;
    }

    public function selectFirst() {
        return $this->collection[0] ?? null;
    }

    // Метод отримання данних рядка таблиці table_name за id
    public function getItem(int $itemId) {

        try {
            $sql = "SELECT * FROM {$this->table_name} WHERE {$this->id_column} = ?;";
            $db = new DB();
            $itemDetails = $db->query($sql, [$itemId]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $itemDetails = [];
        }
        // Повернути масив, що містить данні  
        return array_shift($itemDetails);
    }

    // Метод отримання данних рядка таблиці table_name за id
    public function getItemUniv($column, $value) {
        $this->sql = "SELECT * FROM {$this->table_name} WHERE $column = ?;";
        $db = new DB();
        $results = $db->query($this->sql, array($value));
        // Повернути масив, що містить данні  
        return $this;
    }

    public function getPostValues() {
        $values = [];
        $columns = $this->getColumnsNames();
        foreach ($columns as $column) {
            $column_value = filter_input(INPUT_POST, $column);
            if ($column_value && $column !== $this->id_column) {
                $values[$column] = $column_value;
            }
        }
        return $values;
    }

    // Метод отримання імені колонки з id
    public function getIdName() {
        return $this->id_column;
    }

    // Отримання масиву значень колонки $column_name таблиці $table_name БД
    public function getOneColumnArray($column_name) {
        $db = new DB();
        $sql = "select {$column_name} from {$this->table_name};";
        $results = $db->query($sql);

        // створюємо і повертаємо масив зі значеннями колонки $column_name
        foreach ($results as $result => $value) {
            $column_values_array[] = $value[$column_name];
        }
        return $column_values_array;
    }

    public function IsEditProductFormInputCorrect(): bool {
        $params = array_merge(['id'], $this->FormData());

        // Введений sku 
        $entered_sku = Helper::ClearInput($_POST['sku']);

        switch (FALSE) {
            case!$this->isEmpty($params):
            case (Helper::isNumericInput(array($_POST['price'], $_POST['qty']))):
            case ($entered_sku == $this->getItem(Helper::getId())['sku'] || !($this->IsValueExists($entered_sku, "sku"))):
                return FALSE;
                break;

            default: return TRUE;
        }
    }

    // Отримання імен редагованих колонок
    public function getEditableColumn(): array {
        //імена колонок
        $columns = $this->getColumnsNames();
        //імена колонок, що редагуються
        return array_intersect($columns, array_keys($_POST));
    }

    // Перевірка, чи всі введення у формі клрректні

    public function AllInputsCorrects() {
        // Отримання імен редагованих колонок
        $columns = $this->getEditableColumn();

        // Перевірка правильності введення у кожній колнці
        foreach ($columns as $column) {
            return Helper::FormIcorrectInputMessage($column);
        }
        return TRUE;
    }

    public function getMaxValue(string $column) {
        try {
            $sql = "SELECT MAX($column) AS max_value FROM $this->table_name";            
            $db = new DB();
            $result = $db->query($sql);
            $maxValue = array_shift($result)['max_value'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $maxValue = '0';
        }
        // Повернути масив, що містить данні  
        return $maxValue;
    }

    public function getMinValue(string $column) {
        try {
            $sql = "SELECT MIN($column) AS minValue FROM $this->table_name";
            $db = new DB();
            $result = $db->query($sql);
            $minValue = array_shift($result)['minValue'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $minValue = '0';
        }
        // Повернути масив, що містить данні  
        return $minValue;
    }
}
