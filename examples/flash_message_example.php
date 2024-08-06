<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Helpers\FlashMessageHelper;
use Helpers\SessionHelper;

// Simulate a form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'success':
            FlashMessageHelper::set('Operation completed successfully!', 'success');
            break;
        case 'info':
            FlashMessageHelper::set('Here\'s some important information.', 'info');
            break;
        case 'warning':
            FlashMessageHelper::set('Warning: This action may have consequences.', 'warning');
            break;
        case 'error':
            FlashMessageHelper::set('An error occurred. Please try again.', 'danger');
            break;
        case 'multiple':
            FlashMessageHelper::set('First message', 'info');
            FlashMessageHelper::set('Second message', 'success');
            FlashMessageHelper::set('Third message', 'warning');
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
    <title>Flash Message Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Flash Message Example</h1>

        <?php
        // Display flash messages
        if (FlashMessageHelper::hasMessages()) {
            echo FlashMessageHelper::display();
        }
        ?>

        <form method="post" class="mt-4">
            <div class="mb-3">
                <button type="submit" name="action" value="success" class="btn btn-success">Set Success Message</button>
            </div>
            <div class="mb-3">
                <button type="submit" name="action" value="info" class="btn btn-info">Set Info Message</button>
            </div>
            <div class="mb-3">
                <button type="submit" name="action" value="warning" class="btn btn-warning">Set Warning Message</button>
            </div>
            <div class="mb-3">
                <button type="submit" name="action" value="error" class="btn btn-danger">Set Error Message</button>
            </div>
            <div class="mb-3">
                <button type="submit" name="action" value="multiple" class="btn btn-primary">Set Multiple Messages</button>
            </div>
        </form>
    </div>
</body>
</html>