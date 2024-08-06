<?php

namespace Logger;

class FileLogger implements Logger
{
    private $logFile;
    private $logLevels = ['info', 'warning', 'error', 'debug'];

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
        $this->ensureLogFileExists();
    }

    public function log($message, $level = 'info')
    {
        $this->validateLogLevel($level);

        $logEntry = $this->formatLogEntry($message, $level);
        $this->writeToFile($logEntry);
    }

    public function getLog($limit = null, $offset = null)
    {
        $contents = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($offset !== null) {
            $contents = array_slice($contents, $offset);
        }
        
        if ($limit !== null) {
            $contents = array_slice($contents, 0, $limit);
        }
        
        return array_map(function($line) {
            return json_decode($line, true);
        }, $contents);
    }

    public function clearLog()
    {
        file_put_contents($this->logFile, '');
    }

    private function ensureLogFileExists()
    {
        if (!file_exists($this->logFile)) {
            $directory = dirname($this->logFile);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            touch($this->logFile);
        }
    }

    private function validateLogLevel($level)
    {
        if (!in_array($level, $this->logLevels)) {
            throw new \InvalidArgumentException("Invalid log level: $level");
        }
    }

    private function formatLogEntry($message, $level)
    {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message
        ];
        return json_encode($entry) . PHP_EOL;
    }

    private function writeToFile($logEntry)
    {
        if (file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX) === false) {
            throw new \RuntimeException("Failed to write to log file.");
        }
    }
}