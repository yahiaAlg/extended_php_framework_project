<?php

namespace Database;

use PDO;
use PDOStatement;

class QueryBuilder
{
    private $pdo;
    private $table;
    private $columns = ['*'];
    private $where = [];
    private $orderBy = [];
    private $limit;
    private $offset;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function from(
        string $table,
    ): self
    {
        $this->table[] = $table;
        return $this;
    }

    public function select(array | string $columns): self
    {
        if (is_string($columns) && $columns == "*") {
            $this->columns = ['*'];
            } else {
                $this->columns = $columns;
            }
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->where[] = [$column, $operator, $value];
        return $this;
    }


    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = [$column, $direction];
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        $query = $this->buildQuery();
        $stmt = $this->pdo->prepare($query);
        $this->bindValues($stmt);
        $stmt->execute();
        return $stmt->fetch();
    }


    // fetching all the querySet
    public function all(): array {
        $query = $this->buildQuery();
        $stmt = $this->pdo->prepare($query);
        $this->bindValues($stmt);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function first()
    {
        $this->select(
            '*'
        )
        ->from($this->table)
        ->where('id', '>', 0)
        ->orderBy('id')
        ->limit(1);
        return $this->get();
    }

    // fetching the last record
    public function last() {
        $this->select(
            '*'
        )
        ->from($this->table)
        ->where('id', '>', 0)
        ->orderBy('id', 'DESC')
        ->limit(1);
        return $this->get();
    }

    private function buildQuery(): string
    {
        $query = "SELECT " . implode(', ', $this->columns) . " FROM " . $this->table;

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhereClause();
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . $this->buildOrderByClause();
        }

        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }

        return $query;
    }

    private function buildWhereClause(): string
    {
        return implode(' AND ', array_map(function($condition) {
            return $condition[0] . ' ' . $condition[1] . ' ?';
        }, $this->where));
    }

    private function buildOrderByClause(): string
    {
        return implode(', ', array_map(function($order) {
            return $order[0] . ' ' . $order[1];
        }, $this->orderBy));
    }

    private function bindValues(PDOStatement $stmt): void
    {
        $index = 1;
        foreach ($this->where as $condition) {
            $stmt->bindValue($index++, $condition[2]);
        }
    }

    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    }

    // delete a row
    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$id]);
    }

    // updating a table based on a codition
    public function update(array $data): bool {
        $columns = implode(', ', array_map(function($column) {
            return $column . ' = ?';
            }, array_keys($data)));
            $query = "UPDATE {$this->table} SET $columns WHERE " . $this->
            buildWhereClause();
            $stmt = $this->pdo->prepare($query);
            $this->bindValues($stmt);
            return $stmt->execute();
    }


    public function truncate(): void
    {
        $query = "TRUNCATE TABLE {$this->table}";
        $this->pdo->exec($query);
    }
}