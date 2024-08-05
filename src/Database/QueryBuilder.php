<?php

namespace Database;

use PDO;
use PDOException;

class QueryBuilder
{
    protected $pdo;
    protected $table;
    protected $where = [];
    protected $params = [];
    protected $orderBy = '';
    protected $limit = '';
    protected $offset = '';
    protected $joins = [];
    private $whereConditions = [];
    private $whereParams = [];
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function from($table)
    {
        $this->reset();
        $this->table = $table;
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        $this->addWhereCondition('AND', $column, $operator, $value);
        return $this;
    }

    public function orWhere($column, $operator, $value = null)
    {
        $this->addWhereCondition('OR', $column, $operator, $value);
        return $this;
    }

    private function addWhereCondition($type, $column, $operator, $value)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        $this->whereConditions[] = [$type, $column, $operator, '?'];
        $this->whereParams[] = $value;
    }

    private function buildWhereClause()
    {
        if (empty($this->whereConditions)) {
            return '';
        }

        $whereParts = [];
        foreach ($this->whereConditions as $index => $condition) {
            list($type, $column, $operator, $placeholder) = $condition;
            if ($index === 0) {
                $whereParts[] = "$column $operator $placeholder";
            } else {
                $whereParts[] = "$type $column $operator $placeholder";
            }
        }

        $whereClause = 'WHERE ' . implode(' ', $whereParts);
        // echo "<br/>Debug: Where Clause: " . $whereClause . "<br/>";
        // echo "<br/>Debug: Where Conditions: " . print_r($this->whereConditions, true) . "<br/>";
        return $whereClause;
    }

    public function all()
    {
        if (!empty($this->joins)) {
            $sql = "SELECT {$this->table}.* FROM {$this->table} ";
            $sql .= implode(' ', $this->joins) . ' ';
        } else {
            $sql = "SELECT * FROM {$this->table} ";
        }
        
        $sql .= $this->buildWhereClause();
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->whereParams);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "<br/>Error: " . $e->getMessage() . "<br/>";
            echo "<br/>SQL: " . $sql . "<br/>";
            echo "<br/>Params: " . print_r($this->whereParams, true) . "<br/>";
            // Re-throw the exception if you want to halt execution
            // throw $e;
        }
    }
    
    public function join($table, $condition)
    {
        $this->joins[] = "INNER JOIN $table ON $condition";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function get()
    {
        $sql = $this->buildQuery();
        $stmt = $this->pdo->prepare($sql);
        // echo "<br/>Debug: SQL Query: " . $sql . "<br/>";
        // echo "<br/>Debug: Params: " . print_r($this->whereParams, true) . "<br/>";
        $stmt->execute($this->whereParams);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    }

    public function update($data)
    {
        $set = [];
        $updateParams = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            // Convert boolean false to 0 and true to 1
            if (is_bool($value)) {
                $updateParams[] = $value ? 1 : 0;
            } else {
                $updateParams[] = $value;
            }
        }
        $set = implode(', ', $set);
        
        $sql = "UPDATE {$this->table} SET $set " . $this->buildWhereClause();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($updateParams, $this->whereParams));
        
        return $stmt->rowCount();
    }

    public function delete($id = null)
    {
        if ($id !== null) {
            $this->where('id', '=', $id);
        }
        
        $sql = "DELETE FROM {$this->table} " . $this->buildWhereClause();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->whereParams);
        
        return $stmt->rowCount();
    }

    public function reset()
    {
        $this->table = '';
        $this->whereConditions = [];
        $this->whereParams = [];
        $this->orderBy = '';
        $this->limit = '';
        $this->offset = '';
        $this->joins = [];
        return $this;
    }

    protected function buildQuery()
    {
        if (!empty($this->joins)) {
            $query = "SELECT {$this->table}.* FROM {$this->table} ";
            $query .= implode(' ', $this->joins) . ' ';
        } else {
            $query = "SELECT * FROM {$this->table} ";
        }
        
        $query .= $this->buildWhereClause();
        $query .= " {$this->orderBy} {$this->limit} {$this->offset}";
        return $query;
    }

}