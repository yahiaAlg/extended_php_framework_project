<?php

// Include your autoloader or require all necessary files here

require_once __DIR__ . '/../vendor/autoload.php';

// Rest of your test code...

use Forms\Form;
use Forms\CharField;
use Forms\BooleanField;
use Forms\ChoiceField;
use Forms\DateField;
use Forms\EmailField;
use Forms\FileField;
use Forms\FloatField;
use Forms\IntegerField;
use Forms\PasswordField;
use Forms\ImageField;
use Forms\DateTimeField;
use Forms\TimeField;
use Forms\VideoField;
use Forms\AudioField;

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

function testCharField() {
    $form = new Form();
    $charField = new CharField('name', 'Name', true);
    $form->addField($charField);

    assert(strpos($charField->render(), 'type="text"') !== false, "CharField should render as text input");

    $charField->setValue('John Doe');
    assert($charField->isValid(), "CharField should be valid with non-empty value");

    $charField->setValue('');
    assert(!$charField->isValid(), "CharField should be invalid with empty value when required");
}

function testBooleanField() {
    $form = new Form();
    $boolField = new BooleanField('agree', 'Agree to terms', true);
    $form->addField($boolField);

    assert(strpos($boolField->render(), 'type="checkbox"') !== false, "BooleanField should render as checkbox");

    $boolField->setValue(true);
    assert($boolField->isValid(), "BooleanField should be valid when checked");

    $boolField->setValue(false);
    assert(!$boolField->isValid(), "BooleanField should be invalid when unchecked and required");
}

function testChoiceField() {
    $form = new Form();
    $choices = ['red' => 'Red', 'blue' => 'Blue', 'green' => 'Green'];
    $choiceField = new ChoiceField('color', 'Favorite Color', true, $choices);
    $form->addField($choiceField);

    assert(strpos($choiceField->render(), '<select') !== false, "ChoiceField should render as select element");
    assert(strpos($choiceField->render(), '<option value="red"') !== false, "ChoiceField should include options");

    $choiceField->setValue('blue');
    assert($choiceField->isValid(), "ChoiceField should be valid with a valid choice");

    $choiceField->setValue('yellow');
    assert(!$choiceField->isValid(), "ChoiceField should be invalid with an invalid choice");
}

function testDateField() {
    $form = new Form();
    $dateField = new DateField('birthdate', 'Birth Date', true);
    $form->addField($dateField);

    assert(strpos($dateField->render(), 'type="date"') !== false, "DateField should render as date input");

    $dateField->setValue('1990-01-01');
    assert($dateField->isValid(), "DateField should be valid with correct date format");

    $dateField->setValue('invalid-date');
    assert(!$dateField->isValid(), "DateField should be invalid with incorrect date format");
}

function testEmailField() {
    $form = new Form();
    $emailField = new EmailField('email', 'Email Address', true);
    $form->addField($emailField);

    assert(strpos($emailField->render(), 'type="email"') !== false, "EmailField should render as email input");

    $emailField->setValue('test@example.com');
    assert($emailField->isValid(), "EmailField should be valid with correct email format");

    $emailField->setValue('invalid-email');
    assert(!$emailField->isValid(), "EmailField should be invalid with incorrect email format");
}

function testFileField() {
    $form = new Form();
    $fileField = new FileField('document', 'Upload Document', true);
    $form->addField($fileField);

    assert(strpos($fileField->render(), 'type="file"') !== false, "FileField should render as file input");

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
    $form = new Form();
    $floatField = new FloatField('price', 'Price', true);
    $form->addField($floatField);

    assert(strpos($floatField->render(), 'type="number"') !== false, "FloatField should render as number input");
    assert(strpos($floatField->render(), 'step="any"') !== false, "FloatField should have step='any'");

    $floatField->setValue('10.5');
    assert($floatField->isValid(), "FloatField should be valid with correct float value");

    $floatField->setValue('not-a-number');
    assert(!$floatField->isValid(), "FloatField should be invalid with non-numeric value");
}

function testIntegerField() {
    $form = new Form();
    $intField = new IntegerField('age', 'Age', true);
    $form->addField($intField);

    assert(strpos($intField->render(), 'type="number"') !== false, "IntegerField should render as number input");
    assert(strpos($intField->render(), 'step="1"') !== false, "IntegerField should have step='1'");

    $intField->setValue('25');
    assert($intField->isValid(), "IntegerField should be valid with correct integer value");

    $intField->setValue('25.5');
    assert(!$intField->isValid(), "IntegerField should be invalid with non-integer value");
}

function testPasswordField() {
    $form = new Form();
    $passwordField = new PasswordField('password', 'Password', true);
    $form->addField($passwordField);

    assert(strpos($passwordField->render(), 'type="password"') !== false, "PasswordField should render as password input");

    $passwordField->setValue('secret123');
    assert($passwordField->isValid(), "PasswordField should be valid with non-empty value");

    $passwordField->setValue('');
    assert(!$passwordField->isValid(), "PasswordField should be invalid with empty value when required");
}

function testImageField() {
    $form = new Form();
    $imageField = new ImageField('profile_picture', 'Profile Picture', true);
    $form->addField($imageField);

    assert(strpos($imageField->render(), 'type="file"') !== false, "ImageField should render as file input");
    assert(strpos($imageField->render(), 'accept="image/*"') !== false, "ImageField should accept image files");

    // Simulating image upload
    $_FILES['profile_picture'] = [
        'name' => 'test.jpg',
        'type' => 'image/jpeg',
        'tmp_name' => '/tmp/phpABC123',
        'error' => UPLOAD_ERR_OK,
        'size' => 123456
    ];
    assert($imageField->isValid(), "ImageField should be valid with correct image upload");

    $_FILES['profile_picture']['type'] = 'application/pdf';
    assert(!$imageField->isValid(), "ImageField should be invalid with non-image file type");
}

function testDateTimeField() {
    $form = new Form();
    $dateTimeField = new DateTimeField('event_datetime', 'Event Date and Time', true);
    $form->addField($dateTimeField);

    assert(strpos($dateTimeField->render(), 'type="datetime-local"') !== false, "DateTimeField should render as datetime-local input");

    $dateTimeField->setValue('2023-05-15T14:30');
    assert($dateTimeField->isValid(), "DateTimeField should be valid with correct datetime format");

    $dateTimeField->setValue('invalid-date');
    assert(!$dateTimeField->isValid(), "DateTimeField should be invalid with incorrect datetime format");
}

function testTimeField() {
    $form = new Form();
    $timeField = new TimeField('start_time', 'Start Time', true);
    $form->addField($timeField);

    assert(strpos($timeField->render(), 'type="time"') !== false, "TimeField should render as time input");

    $timeField->setValue('14:30');
    assert($timeField->isValid(), "TimeField should be valid with correct time format");

    $timeField->setValue('25:00');
    assert(!$timeField->isValid(), "TimeField should be invalid with incorrect time format");
}

function testVideoField() {
    $form = new Form();
    $videoField = new VideoField('video_upload', 'Upload Video', false);
    $form->addField($videoField);

    assert(strpos($videoField->render(), 'type="file"') !== false, "VideoField should render as file input");
    assert(strpos($videoField->render(), 'accept="video/*"') !== false, "VideoField should accept video files");

    // Simulating video upload
    $_FILES['video_upload'] = [
        'name' => 'test.mp4',
        'type' => 'video/mp4',
        'tmp_name' => '/tmp/phpXYZ789',
        'error' => UPLOAD_ERR_OK,
        'size' => 1234567
    ];
    assert($videoField->isValid(), "VideoField should be valid with correct video upload");

    $_FILES['video_upload']['type'] = 'image/jpeg';
    assert(!$videoField->isValid(), "VideoField should be invalid with non-video file type");
}

function testAudioField() {
    $form = new Form();
    $audioField = new AudioField('audio_upload', 'Upload Audio', false);
    $form->addField($audioField);

    assert(strpos($audioField->render(), 'type="file"') !== false, "AudioField should render as file input");
    assert(strpos($audioField->render(), 'accept="audio/*"') !== false, "AudioField should accept audio files");

    // Simulating audio upload
    $_FILES['audio_upload'] = [
        'name' => 'test.mp3',
        'type' => 'audio/mpeg',
        'tmp_name' => '/tmp/phpDEF456',
        'error' => UPLOAD_ERR_OK,
        'size' => 234567
    ];
    assert($audioField->isValid(), "AudioField should be valid with correct audio upload");

    $_FILES['audio_upload']['type'] = 'text/plain';
    assert(!$audioField->isValid(), "AudioField should be invalid with non-audio file type");
}

// Run tests
runTest('CharField Test', 'testCharField');
runTest('BooleanField Test', 'testBooleanField');
runTest('ChoiceField Test', 'testChoiceField');
runTest('DateField Test', 'testDateField');
runTest('EmailField Test', 'testEmailField');
runTest('FileField Test', 'testFileField');
runTest('FloatField Test', 'testFloatField');
runTest('IntegerField Test', 'testIntegerField');
runTest('PasswordField Test', 'testPasswordField');
runTest('ImageField Test', 'testImageField');
runTest('DateTimeField Test', 'testDateTimeField');
runTest('TimeField Test', 'testTimeField');
runTest('VideoField Test', 'testVideoField');
runTest('AudioField Test', 'testAudioField');