<?php

// Assuming autoloading is set up correctly
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Validation\Validator;
use Validation\Rules\Required;
use Validation\Rules\Email;
use Validation\Rules\MinLength;
use Validation\Rules\MaxLength;
use Validation\Rules\Numeric;

// Function to display validation results
function displayValidationResult(string $testName, bool $isValid, array $errors = []) {
    echo "$testName: " . ($isValid ? "Valid" : "Invalid") . "\n";
    if (!$isValid) {
        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                echo "  - $field: $error\n";
            }
        }
    }
    echo "\n";
}

// Test 1: Valid user registration
echo "Test 1: Valid user registration\n";
$validator = new Validator();
$validator->addRule('name', new Required())
          ->addRule('name', new MinLength(2))
          ->addRule('name', new MaxLength(50))
          ->addRule('email', new Required())
          ->addRule('email', new Email())
          ->addRule('age', new Required())
          ->addRule('age', new Numeric());

$validData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'age' => '30'
];

$isValid = $validator->validate($validData);
displayValidationResult("Valid user registration", $isValid, $validator->getErrors());

// Test 2: Invalid user registration
echo "Test 2: Invalid user registration\n";
$invalidData = [
    'name' => 'J',
    'email' => 'not-an-email',
    'age' => 'thirty'
];

$isValid = $validator->validate($invalidData);
displayValidationResult("Invalid user registration", $isValid, $validator->getErrors());

// Test 3: Missing required fields
echo "Test 3: Missing required fields\n";
$missingData = [
    'name' => 'Jane Doe'
];

$isValid = $validator->validate($missingData);
displayValidationResult("Missing required fields", $isValid, $validator->getErrors());

// Test 4: Custom validation scenario
echo "Test 4: Custom validation scenario (Password strength)\n";
$passwordValidator = new Validator();
$passwordValidator->addRule('password', new Required())
                  ->addRule('password', new MinLength(8))
                  ->addRule('password', new MaxLength(20));

$validPassword = ['password' => 'StrongP@ss123'];
$isValid = $passwordValidator->validate($validPassword);
displayValidationResult("Valid password", $isValid, $passwordValidator->getErrors());

$invalidPassword = ['password' => 'weak'];
$isValid = $passwordValidator->validate($invalidPassword);
displayValidationResult("Invalid password", $isValid, $passwordValidator->getErrors());

// Test 5: Multiple fields with same rule
echo "Test 5: Multiple fields with same rule\n";
$formValidator = new Validator();
$formValidator->addRule('field1', new Required())
              ->addRule('field2', new Required())
              ->addRule('field3', new Required());

$validForm = ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3'];
$isValid = $formValidator->validate($validForm);
displayValidationResult("Valid form", $isValid, $formValidator->getErrors());

$invalidForm = ['field1' => 'value1', 'field2' => ''];
$isValid = $formValidator->validate($invalidForm);
displayValidationResult("Invalid form", $isValid, $formValidator->getErrors());

echo "All tests completed.\n";