<?php
namespace app\core;
/**
 * Class BaseResourceModel
 */
class ResourceModel
{
    protected string $table;
    protected DB $db;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->db = new DB();
    }

    public function fetchById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function fetchAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql) ?: [];
    }

    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');
        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $this->db->query($sql, $values);

        return $this->db->getLastId();
    }

    public function update(int $id, array $data): void
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
        $this->db->query($sql, $values);
    }

    public function deleteById(int $id): void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    // Add other methods like fetchByCriteria, countAll, etc. as needed
}