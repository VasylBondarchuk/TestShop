<?php

// Enable error reporting
error_reporting(E_ALL);
// Display errors on the screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/**
 * Class Model
 */
abstract class Model {

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

    /**
     * Retrieve a collection of objects.
     * This method must be implemented by child classes.
     * 
     * @return array Collection of objects.
     */
    abstract public function getCollection(): array;

    public function addItem(array $columns, array $data) {
        $db = new DB();
        $placeHolders = trim(str_repeat('?,', count($columns)), ',');
        $columnNames = implode(', ', $columns);
        $sql = "INSERT INTO {$this->table_name} ($columnNames) VALUES ($placeHolders)";
        $flattenedData = array_values($data);
        $db->query($sql, $flattenedData);
    }

    public function editItem(int $id, array $data): Model {
        // Prepare the SQL query
        $db = new DB();
        $editableColumns = $this->getColumnsNames();
        $editableColumns = array_slice($editableColumns, 1); // Exclude the ID column
        $setClause = '';
        $params = [];

        foreach ($editableColumns as $column) {
            if (isset($data[$column])) {
                $setClause .= "$column = ?, ";
                $params[] = $data[$column];
            }
        }

        // Remove the trailing comma and space
        $setClause = rtrim($setClause, ', ');

        // Ensure there is at least one column to update
        if (!empty($setClause)) {
            $sql = "UPDATE {$this->table_name} SET $setClause WHERE {$this->id_column} = ?";
            $params[] = $id;

            // Execute the query
            $db->query($sql, $params);
        }

        // Return the updated model instance
        return $this;
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

    /**
     * Sorts a collection of objects by a specified property.
     *
     * @param array  $collection The collection of objects to be sorted.
     * @param string $property   The property by which to sort the objects.
     * @param string $order      The order of sorting ('asc' for ascending, 'desc' for descending).
     *
     * @return array The sorted collection.
     */
    public function sortCollectionByProperty(array $collection, string $property, ?string $order = 'asc'): array {
        // Define the comparison function based on the property and order
        $comparisonFunction = function ($a, $b) use ($property, $order) {
            if ($order === 'asc') {
                return $a->{$property} <=> $b->{$property};
            } elseif ($order === 'desc') {
                return $b->{$property} <=> $a->{$property};
            }
        };

        // Sort the collection using the comparison function
        usort($collection, $comparisonFunction);

        return $collection;
    }

    public function sortCollectionByProperties(array $collection, array $sortingParams): array {
        // Sort the collection by each property in the specified order        
        foreach ($sortingParams as $property => $order) {
            $collection = $this->sortCollectionByProperty($collection, $property, $order);
        }
        return $collection;
    }

    /**
     * Filter a collection of objects by a specified property within a given range.
     * 
     * @param array $collection The collection of objects to filter
     * @param string $property The name of the property to filter by
     * @param mixed $minValue The minimum value of the range
     * @param mixed $maxValue The maximum value of the range
     * @return array The filtered collection of objects
     */
    public function filterCollectionByPropertyInRange(array $collection, string $property, $minValue, $maxValue): array {
        // Initialize an empty array to store filtered objects
        $filteredCollection = [];

        // Iterate over the collection
        foreach ($collection as $item) {
            // Check if the property exists and its value is within the specified range
            $propertyValue = $item->{$property}();
            if (property_exists($item, $property) && $propertyValue >= $minValue && $propertyValue <= $maxValue) {
                // If so, add the object to the filtered collection
                $filteredCollection[] = $item;
            }
        }

        return $filteredCollection;
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

    public function isValueUnique(string $value, string $columnName): bool {
        $db = new DB();
        $sql = "SELECT COUNT(*) as count FROM $this->table_name WHERE $columnName = ?";
        $result = $db->query($sql, [$value]);
        return $result[0]['count'] === 0;
    }

    public function getColumnsNames(): array {
        $db = new DB();
        $sql = "DESCRIBE $this->table_name";
        $results = $db->query($sql);
        return array_column($results, 'Field');
    }

    public function getFormFieldsFromDbColumns(array $postData): array {
        // Get the column names from the database table
        $dbColumns = $this->getColumnsNames();

        // Filter the keys of the $_POST array to only include those present in the database table
        $formFields = array_intersect($dbColumns, array_keys($postData));

        return $formFields;
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

    public function getMaxValue(string $columnName) {
        try {
            $sql = "SELECT MAX($columnName) AS max_value FROM $this->table_name";
            $db = new DB();
            $result = $db->query($sql);
            $maxValue = array_shift($result)['max_value'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $maxValue = '0';
        }
        return $maxValue;
    }

    public function getMinValue(string $columnName) {
        try {
            $sql = "SELECT MIN($columnName) AS min_value FROM $this->table_name";
            $db = new DB();
            $result = $db->query($sql);
            $minValue = array_shift($result)['min_value'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $minValue = '0';
        }
        return $minValue;
    }

    public function getLastId(): int {
        $db = new DB();
        return $db->getLastId();
    }

    public function isValueExists(string $value, string $columnName): bool {
        $db = new DB();
        $sql = "SELECT COUNT(*) as count FROM {$this->table_name} WHERE $columnName = ?";
        $results = $db->query($sql, [$value]);
        return intval($results[0]['count']) > 0;
    }
}
