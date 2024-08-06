<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Forms\Bootstrap\Form;
use Forms\Bootstrap\CharField;
use Forms\Bootstrap\EmailField;
use Forms\Bootstrap\ChoiceField;
use Forms\Bootstrap\TextAreaField;
use Forms\Bootstrap\RadioField;

// Create a custom user registration form
$userForm = new Form([
    new CharField('username', 'Username', true),
    new EmailField('email', 'Email Address', true),
    new CharField('password', 'Password', true),
    new CharField('confirm_password', 'Confirm Password', true),
    new ChoiceField('country', 'Country', true, [
        'usa' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
        'au' => 'Australia'
    ]),
    new TextAreaField('bio', 'Biography', false, 5),
    new RadioField('gender', 'Gender', true, [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ])
]);

$formSubmitted = false;
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formSubmitted = true;
    $userForm->setData($_POST);

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
    <title>Bootstrap Forms Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">User Registration Form</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($formSubmitted && $userForm->isValid()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Submitted Data:</h2>
                </div>
                <div class="card-body">
                    <?php
                    foreach ($userForm->getData() as $fieldName => $value):
                        if ($fieldName !== 'password' && $fieldName !== 'confirm_password'):
                    ?>
                        <p><strong><?php echo htmlspecialchars($fieldName); ?>:</strong> 
                           <?php echo htmlspecialchars($value?? "no value passed"); ?></p>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" class="needs-validation" novalidate>
            <?php
            foreach ($userForm->getData() as $fieldName => $value):
                echo $userForm->getField($fieldName)->render();
            endforeach;
            ?>
            <button type="submit" class="btn btn-primary mt-3">Register</button>
        </form>

        <h2 class="mt-5">Test Cases</h2>
        <ul class="list-group">
            <li class="list-group-item">Submit the form with all fields empty</li>
            <li class="list-group-item">Enter an invalid email address</li>
            <li class="list-group-item">Enter mismatching passwords</li>
            <li class="list-group-item">Submit a valid form and check if it's processed correctly</li>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>