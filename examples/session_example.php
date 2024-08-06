<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Helpers\SessionHelper;

// Simulate a form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'set':
            SessionHelper::set('user_name', $_POST['user_name']);
            SessionHelper::set('user_email', $_POST['user_email']);
            break;
        case 'remove':
            SessionHelper::remove('user_name');
            break;
        case 'clear':
            SessionHelper::clear();
            break;
    }

    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Session Example</h1>

        <h2 class="mt-4">Current Session Data:</h2>
        <pre>
        <?php
        SessionHelper::start();
        print_r($_SESSION);
        ?>
        </pre>

        <form method="post" class="mt-4">
            <h3>Set Session Data</h3>
            <div class="mb-3">
                <label for="user_name" class="form-label">User Name</label>
                <input type="text" class="form-control" id="user_name" name="user_name" required>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">User Email</label>
                <input type="email" class="form-control" id="user_email" name="user_email" required>
            </div>
            <button type="submit" name="action" value="set" class="btn btn-primary">Set Session Data</button>
        </form>

        <form method="post" class="mt-4">
            <h3>Remove User Name</h3>
            <button type="submit" name="action" value="remove" class="btn btn-warning">Remove User Name</button>
        </form>

        <form method="post" class="mt-4">
            <h3>Clear All Session Data</h3>
            <button type="submit" name="action" value="clear" class="btn btn-danger">Clear Session</button>
        </form>
    </div>
</body>
</html>