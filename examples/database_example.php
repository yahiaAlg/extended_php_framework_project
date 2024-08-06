<?php

// Autoload classes (you might need to adjust this path depending on your setup)
require_once __DIR__.'/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Import necessary classes
use App\Models\UserModel;
use Database\DatabaseConnection;
use Database\QueryBuilder;

// Get database connection
$dbConnection = DatabaseConnection::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);

// Function to create tables
function createTables($dbConnection) {
    $usersTableSchema = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $userRolesTableSchema = "
    CREATE TABLE IF NOT EXISTS user_roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        role VARCHAR(50),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    $dbConnection->createTable('users', $usersTableSchema);
    $dbConnection->createTable('user_roles', $userRolesTableSchema);
}

echo "Starting database tests:\n<br/>";

// Test dropping tables
echo "Testing drop tables functionality:<br/>";
$dbConnection->dropAllTables();

// Test creating tables
echo "\nTesting create tables functionality:<br/>";
createTables($dbConnection);

// Initialize UserModel
$userModel = new UserModel();

echo "\nTesting UserModel functionality:<br/>";

// Create a new user
$newUserId = $userModel->createUser([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password' => password_hash('secret_password', PASSWORD_DEFAULT),
    'first_name' => 'John',
    'last_name' => 'Doe'
], ['user', 'editor']);

if ($newUserId) {
    echo "Created new user with ID: $newUserId<br/>";
} else {
    // If user already exists, retrieve the existing user
    $existingUser = $userModel->getUserByUsername('john_doe');
    if ($existingUser) {
        $newUserId = $existingUser->id;
        echo "Using existing user with ID: $newUserId<br/>";
    } else {
        echo "Error: Unable to create or retrieve user.<br/>";
        exit;
    }
}

// Retrieve the user
$user = $userModel->getUserById($newUserId);
echo "Retrieved user: " . $user->username . " (" . $user->email . ")<br/>";

// Update the user
$userModel->updateUser($newUserId, ['first_name' => 'Johnny']);
$updatedUser = $userModel->getUserById($newUserId);
echo "Updated user first name: " . $updatedUser->first_name . "<br/>";

// Get user roles
$roles = $userModel->getUserRoles($newUserId);
echo "User roles: " . implode(', ', array_column($roles, 'role')) . "<br/>";

// Add a new role
$userModel->addUserRole($newUserId, 'admin');
$updatedRoles = $userModel->getUserRoles($newUserId);
echo "Updated user roles: " . implode(', ', array_column($updatedRoles, 'role')) . "<br/>";

// Check if user has a role
$isAdmin = $userModel->hasRole($newUserId, 'admin');
echo "Is user an admin? " . ($isAdmin ? 'Yes' : 'No') . "<br/>";

// Search for users
$searchResults = $userModel->searchUsers('john');
echo "Search results for 'john': " . count($searchResults) . " user(s) found<br/>";

// Get users by role
$editors = $userModel->getUsersByRole('editor');
echo "Number of editors: " . count($editors) . "<br/>";

// Demonstrate pagination
$page1Users = $userModel->getUsersWithPagination(1, 10);
echo "Users on page 1 (10 per page): " . count($page1Users) . "<br/>";

// Count total users
$totalUsers = $userModel->countUsers();
echo "Total number of users: $totalUsers<br/>";

// Deactivate user
$userModel->deactivateUser($newUserId);
$deactivatedUser = $userModel->getUserById($newUserId);
echo "User active status after deactivation: " . ($deactivatedUser->is_active ? 'Active' : 'Inactive') . "<br/>";

// Get active users
$activeUsers = $userModel->getActiveUsers();
echo "Number of active users: " . count($activeUsers) . "<br/>";

// Delete the user
$userModel->deleteUser($newUserId);
$deletedUser = $userModel->getUserById($newUserId);
echo "User after deletion: " . ($deletedUser ? 'Still exists' : 'Successfully deleted') . "<br/>";

// Test clearing tables
echo "\nTesting clear tables functionality:<br/>";
$dbConnection->clearAllTables();

// Verify tables are empty
$totalUsers = $userModel->countUsers();
echo "Total number of users after clearing tables: $totalUsers<br/>";

echo "\nAll tests completed.<br/>";




/*
good now we have 
```php

```

when executing 
```php

```
which is defined by this
```php

```
and rely on this
```php

```

within this one
```php

```


https://poe.com/s/ltOJgiOBqjYhzp2bWOfC
 */