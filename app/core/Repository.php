<?php

/**
 * Class Repository
 */
class Repository
{
    protected DB $db;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Fetch all records from the database table.
     *
     * @param string $table The name of the database table.
     * @return array|null Array of records or null if an error occurs.
     */
    public function getAll(string $table): ?array
    {
        $sql = "SELECT * FROM $table";
        return $this->db->query($sql);
    }

    /**
     * Find a record by its ID.
     *
     * @param string $table The name of the database table.
     * @param int $id The ID of the record to find.
     * @return array|null The record found, or null if not found.
     */
    public function getById(string $table, int $id): ?array
    {
        $sql = "SELECT * FROM $table WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    /**
     * Insert a new record into the database table.
     *
     * @param string $table The name of the database table.
     * @param array $data The data to insert.
     * @return int The ID of the inserted record, or 0 if insertion fails.
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $values = rtrim(str_repeat('?, ', count($data)), ', ');
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->db->query($sql, array_values($data));
        return $this->db->getLastId();
    }

    /**
     * Update a record in the database table.
     *
     * @param string $table The name of the database table.
     * @param int $id The ID of the record to update.
     * @param array $data The updated data.
     * @return bool True if update is successful, false otherwise.
     */
    public function update(string $table, int $id, array $data): bool
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE $table SET $setClause WHERE id = ?";
        $parameters = array_merge(array_values($data), [$id]);
        return $this->db->query($sql, $parameters) !== false;
    }

    /**
     * Delete a record from the database table.
     *
     * @param string $table The name of the database table.
     * @param int $id The ID of the record to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function delete(string $table, int $id): bool
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        return $this->db->query($sql, [$id]) !== false;
    }
}
