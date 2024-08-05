<?php

namespace Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static $instance = null;
    private $connection;

    private function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance($host, $dbname, $username, $password)
    {
        if (self::$instance === null) {
            self::$instance = new self($host, $dbname, $username, $password);
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // New function to create a table based on DDL SQL
    public function createTable($tableName, $schema)
    {
        try {
            // Check if the table already exists
            $tableExists = $this->connection->query("SHOW TABLES LIKE '$tableName'")->rowCount() > 0;
            
            if ($tableExists) {
                echo "Table '$tableName' already exists. Skipping creation.\n";
                return false;
            }

            // Execute the DDL SQL to create the table
            $this->connection->exec($schema);
            echo "Table '$tableName' created successfully.\n";
            return true;
        } catch (PDOException $e) {
            echo "Error creating table '$tableName': " . $e->getMessage() . "\n";
            return false;
        }
    }
        // New function to clear all records from a table
    public function clearTable($tableName)
    {
        try {
            $this->connection->exec("DELETE FROM $tableName");
            echo "All records deleted from table '$tableName'.\n";
            return true;
        } catch (PDOException $e) {
            echo "Error clearing table '$tableName': " . $e->getMessage() . "\n";
            return false;
        }
    }

    // New function to drop a table
    public function dropTable($tableName)
    {
        try {
            $this->connection->exec("DROP TABLE IF EXISTS $tableName");
            echo "Table '$tableName' dropped successfully.\n";
            return true;
        } catch (PDOException $e) {
            echo "Error dropping table '$tableName': " . $e->getMessage() . "\n";
            return false;
        }
    }

    // New function to clear all tables in the database
    public function clearAllTables()
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->clearTable($table);
        }
        echo "All tables in the database have been cleared.\n";
    }

    // New function to drop all tables in the database
    public function dropAllTables()
    {
        $tables = $this->getTables();
        
        // Disable foreign key checks
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        foreach ($tables as $table) {
            $this->dropTable($table);
        }
        
        // Re-enable foreign key checks
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        echo "All tables in the database have been dropped.\n";
    }

    // Helper function to get all table names in the database
    private function getTables()
    {
        $stmt = $this->connection->query("SHOW TABLES");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Prevent cloning of the instance
    private function __clone() {
    }

    // Prevent unserializing of the instance
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}