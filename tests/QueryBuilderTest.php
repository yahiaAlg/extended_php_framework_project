<?php

require_once __DIR__ . '/../src/Database/DatabaseConnection.php';
require_once __DIR__ . '/../src/Database/QueryBuilder.php';

use Database\DatabaseConnection;
use Database\QueryBuilder;

// Initialize database connection
$db = DatabaseConnection::getInstance('localhost', 'test_database', 'test_user', 'test_password');
$pdo = $db->getConnection();

// Initialize QueryBuilder
$queryBuilder = new QueryBuilder($pdo);

// Test query building
function testQueryBuilding($queryBuilder) {
    $query = $queryBuilder
        ->table('users')
        ->select(['id', 'name', 'email'])
        ->where('age', '>', 18)
        ->orderBy('name', 'ASC')
        ->limit(10)
        ->offset(0);

    $builtQuery = $query->buildQuery();
    $expectedQuery = "SELECT id, name, email FROM users WHERE age > ? ORDER BY name ASC LIMIT 10 OFFSET 0";

    assert($builtQuery === $expectedQuery, 'Query building test failed');
    echo "Query building test passed.\n";
}

// Test query execution
function testQueryExecution($queryBuilder, $pdo) {
    // Create a test table
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50), age INT)");
    
    // Insert test data
    $pdo->exec("INSERT INTO test_users (name, age) VALUES ('Alice', 25), ('Bob', 30), ('Charlie', 35)");

    $results = $queryBuilder
        ->table('test_users')
        ->select(['name', 'age'])
        ->where('age', '>', 28)
        ->orderBy('age', 'DESC')
        ->get();

    assert(count($results) === 2, 'Should return 2 results');
    assert($results[0]['name'] === 'Charlie', 'First result should be Charlie');
    assert($results[1]['name'] === 'Bob', 'Second result should be Bob');

    // Clean up
    $pdo->exec("DROP TABLE test_users");

    echo "Query execution test passed.\n";
}

// Run tests
try {
    testQueryBuilding($queryBuilder);
    testQueryExecution($queryBuilder, $pdo);
    echo "All tests passed successfully.\n";
} catch (AssertionError $e) {
    echo "Test failed: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}