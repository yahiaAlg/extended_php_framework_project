<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Forms\Form;
use Forms\CharField;
use Forms\EmailField;
use Forms\PasswordField;
use Forms\IntegerField;
use Forms\DateField;
use Forms\FileField;

// Create a custom user registration form
$userForm = new Form([
    new CharField('username', 'Username', true, 3, 50),
    new EmailField('email', 'Email Address', true),
    new PasswordField('password', 'Password', true, 8),
    new PasswordField('confirm_password', 'Confirm Password', true),
    new CharField('full_name', 'Full Name', true, 2, 100),
    new IntegerField('age', 'Age', true, 18, 120),
    new DateField('birthdate', 'Date of Birth', true),
    new FileField('profile_picture', 'Profile Picture', false, ['jpg', 'jpeg', 'png'], 1048576) // Max 1MB
]);


$formSubmitted = false;
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formSubmitted = true;
    $userForm->setData($_POST);
    
    if (isset($_FILES['profile_picture'])) {
        $userForm->setData(['profile_picture' => $_FILES['profile_picture']]);
    }

    if ($userForm->isValid()) {
        $data = $userForm->getData();
        
        // Check if passwords match
        if ($data['password'] !== $data['confirm_password']) {
            $userForm->getErrors()['confirm_password'] = ['Passwords do not match.'];
        } else {
            // Process the form data (e.g., save to database)
            $message = "Registration successful!";
            // Don't reset the form here to allow displaying the submitted data
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form Example</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 5px; margin-top: 5px; }
        .error { color: red; font-size: 0.9em; }
        .success { color: green; font-weight: bold; }
        .submitted-data { background-color: #f0f0f0; padding: 15px; margin-top: 20px; border-radius: 5px; }
        .submitted-data h2 { margin-top: 0; }
    </style>
</head>
<body>
    <h1>User Registration Form</h1>
    
    <?php if ($message): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($formSubmitted): ?>
        <div class="submitted-data">
            <h2>Submitted Data:</h2>
            <?php
            foreach ($userForm->getData() as $fieldName => $value):
                if ($fieldName !== 'password' && $fieldName !== 'confirm_password'):
                    echo '<p><strong>' . htmlspecialchars($fieldName) . ':</strong> ';
                    if ($fieldName === 'profile_picture' && isset($_FILES['profile_picture']['name'])):
                        echo "<pre>";
                        print_r($_FILES['profile_picture']);
                        echo "</pre>";
                    else:
                        echo htmlspecialchars($value?? "no value passed");
                    endif;
                    echo '</p>';
                endif;
            endforeach;
            ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?php
        foreach ($userForm->getData() as $fieldName => $value):
            $field = $userForm->getField($fieldName);
            echo $field->render();
            if (isset($userForm->getErrors()[$fieldName])):
                foreach ($userForm->getErrors()[$fieldName] as $error):
        ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php
                endforeach;
            endif;
        endforeach;
        ?>
        <input type="submit" value="Register">
    </form>

    <h2>Test Cases</h2>
    <ul>
        <li>Submit the form with all fields empty</li>
        <li>Enter an invalid email address</li>
        <li>Enter a username shorter than 3 characters</li>
        <li>Enter a password shorter than 8 characters</li>
        <li>Enter mismatching passwords</li>
        <li>Enter an age below 18 or above 120</li>
        <li>Enter an invalid date for the birthdate</li>
        <li>Try uploading a file larger than 1MB</li>
        <li>Try uploading a file with an invalid extension (e.g., .txt)</li>
        <li>Submit a valid form and check if it's processed correctly</li>
    </ul>
</body>
</html>
