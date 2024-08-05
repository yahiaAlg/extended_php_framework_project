<?php

namespace Logger;

use Database\DatabaseConnection;
use Database\QueryBuilder;

class DatabaseLogger implements Logger
{
    private $queryBuilder;
    private $tableName;

    public function __construct(DatabaseConnection $dbConnection, string $tableName = 'logs')
    {
        $this->queryBuilder = new QueryBuilder($dbConnection->getConnection());
        $this->tableName = $tableName;
    }

    public function log(string $message, string $level = 'INFO', array $context = []): void
    {
        $logEntry = [
            'message' => $message,
            'level' => $level,
            'context' => json_encode($context),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->queryBuilder->table($this->tableName)->insert($logEntry);
    }

    public function getLogs(int $limit = 10, int $offset = 0): array
    {
        return $this->queryBuilder
            ->table($this->tableName)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function clearLogs(): void
    {
        $this->queryBuilder->table($this->tableName)->truncate();
    }
}