<?php

namespace Core\Models;
use Database\DatabaseConnection;
use Database\QueryBuilder;
abstract class Model
{
    // This is a basic Model class. In a real application, you'd add database
    // connection logic, query methods, etc.
    protected $db;
    protected $queryBuilder;
    // table name
    protected $table;
    public function __construct()
    {
        // Initialize database connection
        $this->db = new DatabaseConnection(
            DB_HOST,
            DB_NAME,
            DB_USER,
            DB_PASS,
        );
        // initializing a queryBuilder object property
        $this->queryBuilder = new QueryBuilder($this->db->getConnection());
    }
}