<?php

namespace Logger;

use Exception;

class FileLogger implements Logger
{
    private $logFile;
    private $maxFileSize;
    private $backupCount;

    public function __construct(string $logFile, int $maxFileSize = 5242880, int $backupCount = 5)
    {
        $this->logFile = $logFile;
        $this->maxFileSize = $maxFileSize; // Default to 5MB
        $this->backupCount = $backupCount;
    }

    public function log(string $message, string $level = 'INFO', array $context = []): void
    {
        $logEntry = $this->formatLogEntry($message, $level, $context);

        $this->writeLog($logEntry);
        $this->rotateLogIfNeeded();
    }

    private function formatLogEntry(string $message, string $level, array $context): string
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context) : '';
        return "[$timestamp] [$level] $message $contextString" . PHP_EOL;
    }

    private function writeLog(string $logEntry): void
    {
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }

        if (file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX) === false) {
            throw new Exception("Failed to write to log file: {$this->logFile}");
        }
    }

    private function rotateLogIfNeeded(): void
    {
        if (!file_exists($this->logFile) || filesize($this->logFile) < $this->maxFileSize) {
            return;
        }

        for ($i = $this->backupCount; $i > 0; $i--) {
            $srcFile = $i > 1 ? "{$this->logFile}.{$i}" : $this->logFile;
            $destFile = "{$this->logFile}." . ($i + 1);

            if (file_exists($srcFile)) {
                rename($srcFile, $destFile);
            }
        }

        if (file_exists($this->logFile)) {
            rename($this->logFile, "{$this->logFile}.1");
        }
    }

    public function getLogs(int $limit = 100, int $offset = 0): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $logs = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = array_reverse($logs); // Most recent logs first
        return array_slice($logs, $offset, $limit);
    }

    public function clearLogs(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        for ($i = 1; $i <= $this->backupCount; $i++) {
            $backupFile = "{$this->logFile}.{$i}";
            if (file_exists($backupFile)) {
                unlink($backupFile);
            }
        }
    }
}