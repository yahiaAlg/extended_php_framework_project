<?php

require_once __DIR__ . '/../src/Database/DatabaseConnection.php';

use Database\DatabaseConnection;

// Test Singleton instance
function testSingletonInstance() {
    $db1 = DatabaseConnection::getInstance('localhost', 'test_database', 'test_user', 'test_password');
    $db2 = DatabaseConnection::getInstance('localhost', 'test_database', 'test_user', 'test_password');

    assert($db1 === $db2, 'DatabaseConnection should return the same instance');
    echo "Singleton instance test passed.\n";
}

// Test connection is valid PDO
function testConnectionIsValidPDO() {
    $db = DatabaseConnection::getInstance('localhost', 'test_database', 'test_user', 'test_password');
    $connection = $db->getConnection();

    assert($connection instanceof PDO, 'Connection should be an instance of PDO');
    echo "Valid PDO connection test passed.\n";
}

// Test connection can execute query
function testConnectionCanExecuteQuery() {
    $db = DatabaseConnection::getInstance('localhost', 'test_database', 'test_user', 'test_password');
    $connection = $db->getConnection();

    // Create a test table
    $connection->exec("CREATE TABLE IF NOT EXISTS test_users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50))");

    // Insert a test record
    $connection->exec("INSERT INTO test_users (name) VALUES ('Test User')");

    // Query the test record
    $stmt = $connection->query("SELECT * FROM test_users WHERE name = 'Test User'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    assert($result['name'] === 'Test User', 'Should be able to insert and retrieve data');

    // Clean up
    $connection->exec("DROP TABLE test_users");

    echo "Query execution test passed.\n";
}

// Run tests
try {
    testSingletonInstance();
    testConnectionIsValidPDO();
    testConnectionCanExecuteQuery();
    echo "All tests passed successfully.\n";
} catch (AssertionError $e) {
    echo "Test failed: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}