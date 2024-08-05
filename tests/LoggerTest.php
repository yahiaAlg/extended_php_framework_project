<?php

require_once __DIR__ . '/../src/Logger/Logger.php';
require_once __DIR__ . '/../src/Logger/DatabaseLogger.php';
require_once __DIR__ . '/../src/Logger/FileLogger.php';
require_once __DIR__ . '/../src/Database/DatabaseConnection.php';

use Logger\Logger;
use Logger\DatabaseLogger;
use Logger\FileLogger;
use Database\DatabaseConnection;

class LoggerTest
{
    private $databaseLogger;
    private $fileLogger;
    private $tempLogFile;

    public function runTests()
    {
        $this->setUp();

        $this->testLoggerInterfaces();
        $this->testDatabaseLoggerLog();
        $this->testFileLoggerLog();
        $this->testDatabaseLoggerGetLogs();
        $this->testFileLoggerGetLogs();
        $this->testDatabaseLoggerClearLogs();
        $this->testFileLoggerClearLogs();
        $this->testFileLoggerRotation();

        $this->tearDown();
    }

    private function setUp()
    {
        // Setup DatabaseLogger
        $mockConnection = new class extends DatabaseConnection {
            public function getConnection() {
                return new PDO('sqlite::memory:');
            }
        };
        $this->databaseLogger = new DatabaseLogger($mockConnection);

        // Setup FileLogger
        $this->tempLogFile = tempnam(sys_get_temp_dir(), 'log_');
        $this->fileLogger = new FileLogger($this->tempLogFile);
    }

    private function tearDown()
    {
        if (file_exists($this->tempLogFile)) {
            unlink($this->tempLogFile);
        }
    }

    private function assert($condition, $message)
    {
        if (!$condition) {
            throw new Exception("Assertion failed: $message");
        }
    }

    public function testLoggerInterfaces()
    {
        $this->assert($this->databaseLogger instanceof Logger, "DatabaseLogger should implement Logger interface");
        $this->assert($this->fileLogger instanceof Logger, "FileLogger should implement Logger interface");
        echo "Logger interfaces test passed.\n";
    }

    public function testDatabaseLoggerLog()
    {
        $this->databaseLogger->log('Test message', 'INFO', ['key' => 'value']);
        $logs = $this->databaseLogger->getLogs();
        $this->assert(count($logs) === 1, "Should have 1 log entry");
        $this->assert(strpos($logs[0]['message'], 'Test message') !== false, "Log should contain 'Test message'");
        echo "DatabaseLogger log test passed.\n";
    }

    public function testFileLoggerLog()
    {
        $this->fileLogger->log('Test message', 'INFO', ['key' => 'value']);
        $logs = $this->fileLogger->getLogs();
        $this->assert(count($logs) === 1, "Should have 1 log entry");
        $this->assert(strpos($logs[0], 'Test message') !== false, "Log should contain 'Test message'");
        echo "FileLogger log test passed.\n";
    }

    public function testDatabaseLoggerGetLogs()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->databaseLogger->log("Message $i");
        }
        $logs = $this->databaseLogger->getLogs(3, 1);
        $this->assert(count($logs) === 3, "Should have 3 log entries");
        $this->assert(strpos($logs[0]['message'], 'Message 3') !== false, "First log should be 'Message 3'");
        echo "DatabaseLogger getLogs test passed.\n";
    }

    public function testFileLoggerGetLogs()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->fileLogger->log("Message $i");
        }
        $logs = $this->fileLogger->getLogs(3, 1);
        $this->assert(count($logs) === 3, "Should have 3 log entries");
        $this->assert(strpos($logs[0], 'Message 3') !== false, "First log should be 'Message 3'");
        echo "FileLogger getLogs test passed.\n";
    }

    public function testDatabaseLoggerClearLogs()
    {
        $this->databaseLogger->log('Test message');
        $this->databaseLogger->clearLogs();
        $logs = $this->databaseLogger->getLogs();
        $this->assert(empty($logs), "Logs should be empty after clearing");
        echo "DatabaseLogger clearLogs test passed.\n";
    }

    public function testFileLoggerClearLogs()
    {
        $this->fileLogger->log('Test message');
        $this->fileLogger->clearLogs();
        $logs = $this->fileLogger->getLogs();
        $this->assert(empty($logs), "Logs should be empty after clearing");
        echo "FileLogger clearLogs test passed.\n";
    }

    public function testFileLoggerRotation()
    {
        $rotationLogger = new FileLogger($this->tempLogFile, 10, 2); // 10 bytes max, 2 backups
        for ($i = 0; $i < 5; $i++) {
            $rotationLogger->log(str_repeat('a', 10)); // Each log is 10 bytes
        }
        $this->assert(file_exists($this->tempLogFile), "Main log file should exist");
        $this->assert(file_exists($this->tempLogFile . '.1'), "Backup log file 1 should exist");
        $this->assert(file_exists($this->tempLogFile . '.2'), "Backup log file 2 should exist");
        $this->assert(!file_exists($this->tempLogFile . '.3'), "Backup log file 3 should not exist");
        echo "FileLogger rotation test passed.\n";
    }
}

// Run the tests
$test = new LoggerTest();
$test->runTests();