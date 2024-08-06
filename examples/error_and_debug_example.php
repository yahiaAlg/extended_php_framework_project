<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Helpers\ErrorHelpers;
use Helpers\DebugHelpers;

// Set up error and exception handlers
set_error_handler([ErrorHelpers::class, 'handleError']);
set_exception_handler([ErrorHelpers::class, 'handleException']);

// Function to trigger different types of errors and exceptions
function triggerErrorOrException($type)
{
    switch ($type) {
        case 'notice':
            $undefined_var;
            break;
        case 'warning':
            array_key_exists('key', null);
            break;
        case 'error':
            non_existent_function();
            break;
        case 'exception':
            throw new Exception("This is a test exception");
            break;
        case 'user_error':
            trigger_error("This is a user error", E_USER_ERROR);
            break;
    }
}

// Function to demonstrate DebugHelpers
function demonstrateDebugHelpers()
{
    $testArray = ['apple', 'banana', 'cherry'];
    $testObject = new stdClass();
    $testObject->name = "Test Object";
    $testObject->value = 42;

    echo "<h3>DebugHelpers::dump() example:</h3>";
    DebugHelpers::dump($testArray);

    echo "<h3>DebugHelpers::dd() example (This will end script execution):</h3>";
    echo "Uncomment the line below to test dd()";
    // DebugHelpers::dd($testObject);

    echo "<h3>DebugHelpers::backtrace() example:</h3>";
    DebugHelpers::backtrace();

    echo "<h3>DebugHelpers::logMessage() example:</h3>";
    DebugHelpers::logMessage("This is a test log message");
    echo "A message has been logged to the app.log file.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error and Debug Helpers Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Error and Debug Helpers Example</h1>

        <h2 class="mt-4">Error Handling Examples</h2>
        <form method="post" class="mt-3">
            <div class="mb-3">
                <select name="error_type" class="form-select">
                    <option value="notice">Trigger Notice</option>
                    <option value="warning">Trigger Warning</option>
                    <option value="error">Trigger Error</option>
                    <option value="exception">Throw Exception</option>
                    <option value="user_error">Trigger User Error</option>
                </select>
            </div>
            <button type="submit" name="action" value="trigger_error" class="btn btn-primary">Trigger Error/Exception</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'trigger_error') {
            $errorType = $_POST['error_type'] ?? 'notice';
            triggerErrorOrException($errorType);
        }
        ?>

        <h2 class="mt-5">Debug Helpers Examples</h2>
        <?php demonstrateDebugHelpers(); ?>
    </div>
</body>
</html>