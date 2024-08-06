<?php

// Assuming autoloading is set up correctly
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Helpers\MailingHelper;

// Function to display results
function displayResult($description, $result) {
    echo "$description: " . ($result ? "Success" : "Failure") . "\n";
}

// Create a new MailingHelper instance
$mailer = new MailingHelper();

// Test 1: Send a simple email
echo "Test 1: Sending a simple email\n";
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com', 'Sender Name')
                 ->subject('Test Email')
                 ->message('<h1>Hello!</h1><p>This is a test email.</p>')
                 ->send();
displayResult("Simple email", $result);

// Reset mailer for next test
$mailer = new MailingHelper();

// Test 2: Send an email with CC and BCC
echo "\nTest 2: Sending an email with CC and BCC\n";
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com')
                 ->cc('cc@example.com')
                 ->bcc('bcc@example.com')
                 ->subject('Email with CC and BCC')
                 ->message('<p>This email has CC and BCC recipients.</p>')
                 ->send();
displayResult("Email with CC and BCC", $result);

// Reset mailer for next test
$mailer = new MailingHelper();

// Test 3: Send an email with an attachment
echo "\nTest 3: Sending an email with an attachment\n";
$attachmentPath = __DIR__ . '/test_attachment.txt';
file_put_contents($attachmentPath, 'This is a test attachment.');

$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com')
                 ->subject('Email with Attachment')
                 ->message('<p>This email has an attachment.</p>')
                 ->attach($attachmentPath)
                 ->send();
displayResult("Email with attachment", $result);

// Clean up the test attachment
unlink($attachmentPath);

// Reset mailer for next test
$mailer = new MailingHelper();

// Test 4: Send an email with a reply-to address
echo "\nTest 4: Sending an email with a reply-to address\n";
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com')
                 ->replyTo('reply@example.com')
                 ->subject('Email with Reply-To')
                 ->message('<p>Please reply to a different address.</p>')
                 ->send();
displayResult("Email with reply-to", $result);

// Test 5: Attempt to send an email without required fields
echo "\nTest 5: Attempting to send an email without required fields\n";
$mailer = new MailingHelper();
try {
    $result = $mailer->from('sender@example.com')->send();
    displayResult("Email without required fields", false);
} catch (\Exception $e) {
    echo "Caught expected exception: " . $e->getMessage() . "\n";
}

echo "\nAll tests completed.\n";