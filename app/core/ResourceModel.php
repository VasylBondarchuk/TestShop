<?php

namespace app\core;

class ResourceModel {

    protected string $table;
    protected string $idColumn;
    protected DB $db;
    protected Logger $logger;

    public function __construct(string $table, string $idColumn) {
        $this->table = $table;
        $this->idColumn = $idColumn;
        $this->db = new DB();
        $this->logger = new Logger();
    }

    public function fetchById(int $id): ?array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE {$this->idColumn} = ?";
            return $this->db->query($sql, [$id])[0] ?? null;
        } catch (\PDOException $e) {
            $this->logger->log("Error fetching record by ID: {$e->getMessage()}");
            return null;
        }
    }

    public function fetchByParam(string $paramName, $paramValue): ?array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE {$paramName} = ?";
            return $this->db->query($sql, [$paramValue])[0] ?? null;
        } catch (\PDOException $e) {
            $this->logger->log("Error fetching record by param: {$e->getMessage()}");
            return null;
        }
    }

    public function fetchAll(): array {
        try {
            $sql = "SELECT * FROM {$this->table}";
            return $this->db->query($sql) ?: [];
        } catch (\PDOException $e) {
            $this->logger->log("Error fetching all records: {$e->getMessage()}");
            return [];
        }
    }

    public function insert(array $data): int {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');
            $values = array_values($data);

            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $this->db->query($sql, $values);

            return $this->db->getLastId();
        } catch (\PDOException $e) {
            $this->logger->log("Error inserting record: {$e->getMessage()}");
            return 0;
        }
    }

    public function update(int $id, array $data): void {
        try {
            $set = implode(' = ?, ', array_keys($data)) . ' = ?';
            $values = array_values($data);
            $values[] = $id;

            $sql = "UPDATE {$this->table} SET $set WHERE {$this->idColumn} = ?";
            $this->db->query($sql, $values);
        } catch (\PDOException $e) {
            $this->logger->log("Error updating record: {$e->getMessage()}");
        }
    }

    public function deleteById(int $id): void {
        try {
            $sql = "DELETE FROM {$this->table} WHERE {$this->idColumn} = ?";
            $this->db->query($sql, [$id]);
        } catch (\PDOException $e) {
            $this->logger->log("Error deleting record by ID: {$e->getMessage()}");
        }
    }

    // Add other methods like fetchByCriteria, countAll, etc. as needed
}
