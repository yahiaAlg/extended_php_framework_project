<?php

namespace Logger;

use Database\DatabaseConnection;

class DatabaseLogger implements Logger
{
    private $dbConnection;
    private $logTable = 'logs';

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        $this->ensureLogTableExists();
    }

    private function ensureLogTableExists()
    {
        $schema = "
            CREATE TABLE IF NOT EXISTS {$this->logTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                message TEXT NOT NULL,
                level VARCHAR(20) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->dbConnection->createTable($this->logTable, $schema);
    }

    public function log($message, $level = 'info')
    {
        $pdo = $this->dbConnection->getConnection();
        $query = "INSERT INTO {$this->logTable} (message, level) VALUES (:message, :level)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['message' => $message, 'level' => $level]);
    }

    public function getLog($limit = null, $offset = null)
    {
        $pdo = $this->dbConnection->getConnection();
        $query = "SELECT * FROM {$this->logTable} ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $pdo->prepare($query);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function clearLog()
    {
        $this->dbConnection->clearTable($this->logTable);
    }
}