<?php

namespace Logger;

interface Logger
{
    /**
     * Log a message with an optional level and context.
     *
     * @param string $message The log message
     * @param string $level The log level (e.g., INFO, WARNING, ERROR)
     * @param array $context Additional context data for the log entry
     * @return void
     */
    public function log(string $message, string $level = 'INFO', array $context = []): void;

    /**
     * Retrieve logs with optional limit and offset for pagination.
     *
     * @param int $limit Maximum number of log entries to retrieve
     * @param int $offset Number of log entries to skip
     * @return array An array of log entries
     */
    public function getLogs(int $limit = 100, int $offset = 0): array;

    /**
     * Clear all logs.
     *
     * @return void
     */
    public function clearLogs(): void;
}