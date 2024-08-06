<?php

namespace Logger;

interface Logger
{
    /**
     * Log a message with a specified log level.
     *
     * @param mixed $message The message to log
     * @param string $level The log level (e.g., 'info', 'warning', 'error', 'debug')
     * @return void
     */
    public function log($message, $level = 'info');

    /**
     * Retrieve log entries.
     *
     * @param int|null $limit The maximum number of log entries to retrieve (optional)
     * @param int|null $offset The number of log entries to skip (optional)
     * @return array An array of log entries
     */
    public function getLog($limit = null, $offset = null);

    /**
     * Clear all log entries.
     *
     * @return void
     */
    public function clearLog();
}