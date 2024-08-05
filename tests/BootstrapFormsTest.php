<?php

require_once __DIR__ . '/../autoload.php';

use Forms\Bootstrap\Form as BootstrapForm;
use Forms\Bootstrap\CharField;
use Forms\Bootstrap\BooleanField;
use Forms\Bootstrap\ChoiceField;
use Forms\Bootstrap\DateField;
use Forms\Bootstrap\DateTimeField;
use Forms\Bootstrap\EmailField;
use Forms\Bootstrap\FileField;
use Forms\Bootstrap\FloatField;
use Forms\Bootstrap\ImageField;
use Forms\Bootstrap\IntegerField;
use Forms\Bootstrap\PasswordField;
use Forms\Bootstrap\TimeField;
use Forms\Bootstrap\VideoField;
use Forms\Bootstrap\AudioField;

function runTest($testName, $testFunction) {
    echo "Running test: $testName\n";
    try {
        $testFunction();
        echo "Test passed!\n\n";
    } catch (Exception $e) {
        echo "Test failed: " . $e->getMessage() . "\n\n";
    }
}

function assert($condition, $message) {
    if (!$condition) {
        throw new Exception($message);
    }
}

// Test functions

function testBootstrapForm() {
    $form = new BootstrapForm();
    assert($form instanceof BootstrapForm, "Should create a BootstrapForm instance");
    
    $renderedForm = $form->render();
    assert(strpos($renderedForm, 'class="form') !== false, "BootstrapForm should have a form class");
}

function testCharField() {
    $form = new BootstrapForm();
    $charField = new CharField('name', 'Name', true);
    $form->addField($charField);

    $rendered = $charField->render();
    assert(strpos($rendered, 'class="form-control"') !== false, "CharField should have form-control class");
    assert(strpos($rendered, 'class="form-label"') !== false, "CharField should have a label with form-label class");

    $charField->setValue('John Doe');
    assert($charField->isValid(), "CharField should be valid with non-empty value");

    $charField->setValue('');
    assert(!$charField->isValid(), "CharField should be invalid with empty value when required");
}

function testBooleanField() {
    $form = new BootstrapForm();
    $boolField = new BooleanField('agree', 'I agree to the terms', true);
    $form->addField($boolField);

    $rendered = $boolField->render();
    assert(strpos($rendered, 'type="checkbox"') !== false, "BooleanField should render a checkbox");
    assert(strpos($rendered, 'class="form-check-input"') !== false, "BooleanField should have form-check-input class");

    $boolField->setValue(true);
    assert($boolField->isValid(), "BooleanField should be valid when checked");

    $boolField->setValue(false);
    assert(!$boolField->isValid(), "BooleanField should be invalid when unchecked and required");
}

function testChoiceField() {
    $form = new BootstrapForm();
    $options = ['red' => 'Red', 'blue' => 'Blue', 'green' => 'Green'];
    $choiceField = new ChoiceField('color', 'Color', true, $options);
    $form->addField($choiceField);

    $rendered = $choiceField->render();
    assert(strpos($rendered, '<select') !== false, "ChoiceField should render a select element");
    assert(strpos($rendered, 'class="form-select"') !== false, "ChoiceField should have form-select class");

    $choiceField->setValue('blue');
    assert($choiceField->isValid(), "ChoiceField should be valid with a valid option");

    $choiceField->setValue('yellow');
    assert(!$choiceField->isValid(), "ChoiceField should be invalid with an invalid option");
}

function testDateField() {
    $form = new BootstrapForm();
    $dateField = new DateField('birthday', 'Birthday', true);
    $form->addField($dateField);

    $rendered = $dateField->render();
    assert(strpos($rendered, 'type="date"') !== false, "DateField should render a date input");
    assert(strpos($rendered, 'class="form-control"') !== false, "DateField should have form-control class");

    $dateField->setValue('2023-05-15');
    assert($dateField->isValid(), "DateField should be valid with correct date format");

    $dateField->setValue('invalid-date');
    assert(!$dateField->isValid(), "DateField should be invalid with incorrect date format");
}

function testEmailField() {
    $form = new BootstrapForm();
    $emailField = new EmailField('email', 'Email Address', true);
    $form->addField($emailField);

    $rendered = $emailField->render();
    assert(strpos($rendered, 'type="email"') !== false, "EmailField should render an email input");
    assert(strpos($rendered, 'class="form-control"') !== false, "EmailField should have form-control class");

    $emailField->setValue('test@example.com');
    assert($emailField->isValid(), "EmailField should be valid with correct email format");

    $emailField->setValue('invalid-email');
    assert(!$emailField->isValid(), "EmailField should be invalid with incorrect email format");
}

function testFileField() {
    $form = new BootstrapForm();
    $fileField = new FileField('document', 'Upload Document', true);
    $form->addField($fileField);

    $rendered = $fileField->render();
    assert(strpos($rendered, 'type="file"') !== false, "FileField should render a file input");
    assert(strpos($rendered, 'class="form-control"') !== false, "FileField should have form-control class");

    // Simulating file upload
    $_FILES['document'] = [
        'name' => 'test.pdf',
        'type' => 'application/pdf',
        'tmp_name' => '/tmp/phpTEST123',
        'error' => UPLOAD_ERR_OK,
        'size' => 12345
    ];
    assert($fileField->isValid(), "FileField should be valid with correct file upload");

    $_FILES['document']['error'] = UPLOAD_ERR_INI_SIZE;
    assert(!$fileField->isValid(), "FileField should be invalid with upload error");
}

function testFloatField() {
    $form = new BootstrapForm();
    $floatField = new FloatField('price', 'Price', true);
    $form->addField($floatField);

    $rendered = $floatField->render();
    assert(strpos($rendered, 'type="number"') !== false, "FloatField should render a number input");
    assert(strpos($rendered, 'step="any"') !== false, "FloatField should have step attribute set to any");

    $floatField->setValue('10.5');
    assert($floatField->isValid(), "FloatField should be valid with a valid float");

    $floatField->setValue('not a number');
    assert(!$floatField->isValid(), "FloatField should be invalid with a non-numeric value");
}

function testIntegerField() {
    $form = new BootstrapForm();
    $intField = new IntegerField('quantity', 'Quantity', true);
    $form->addField($intField);

    $rendered = $intField->render();
    assert(strpos($rendered, 'type="number"') !== false, "IntegerField should render a number input");
    assert(strpos($rendered, 'step="1"') !== false, "IntegerField should have step attribute set to 1");

    $intField->setValue('10');
    assert($intField->isValid(), "IntegerField should be valid with a valid integer");

    $intField->setValue('10.5');
    assert(!$intField->isValid(), "IntegerField should be invalid with a non-integer value");
}

function testPasswordField() {
    $form = new BootstrapForm();
    $passwordField = new PasswordField('password', 'Password', true);
    $form->addField($passwordField);

    $rendered = $passwordField->render();
    assert(strpos($rendered, 'type="password"') !== false, "PasswordField should render a password input");
    assert(strpos($rendered, 'class="form-control"') !== false, "PasswordField should have form-control class");

    $passwordField->setValue('strongpassword');
    assert($passwordField->isValid(), "PasswordField should be valid with non-empty value");

    $passwordField->setValue('');
    assert(!$passwordField->isValid(), "PasswordField should be invalid with empty value when required");
}

function testTimeField() {
    $form = new BootstrapForm();
    $timeField = new TimeField('appointment', 'Appointment Time', true);
    $form->addField($timeField);

    $rendered = $timeField->render();
    assert(strpos($rendered, 'type="time"') !== false, "TimeField should render a time input");
    assert(strpos($rendered, 'class="form-control"') !== false, "TimeField should have form-control class");

    $timeField->setValue('14:30');
    assert($timeField->isValid(), "TimeField should be valid with correct time format");

    $timeField->setValue('invalid-time');
    assert(!$timeField->isValid(), "TimeField should be invalid with incorrect time format");
}

// Run tests
runTest('BootstrapForm Test', 'testBootstrapForm');
runTest('CharField Test', 'testCharField');
runTest('BooleanField Test', 'testBooleanField');
runTest('ChoiceField Test', 'testChoiceField');
runTest('DateField Test', 'testDateField');
runTest('EmailField Test', 'testEmailField');
runTest('FileField Test', 'testFileField');
runTest('FloatField Test', 'testFloatField');
runTest('IntegerField Test', 'testIntegerField');
runTest('PasswordField Test', 'testPasswordField');
runTest('TimeField Test', 'testTimeField');