<?php
use Helpers\MailingHelper;

$mailer = new MailingHelper();
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com', 'Sender Name')
                 ->subject('Test Email')
                 ->message('<h1>Hello!</h1><p>This is a test email.</p>')
                 ->send();

if ($result) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
unset($mailer);

# Using CC, BCC, and Reply-To:


$mailer = new MailingHelper();
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com', 'Sender Name')
                 ->cc('cc@example.com')
                 ->bcc('bcc@example.com')
                 ->replyTo('reply@example.com')
                 ->subject('Test Email with CC and BCC')
                 ->message('<h1>Hello!</h1><p>This is a test email with CC and BCC.</p>')
                 ->send();

# Sending an email with an attachment:
unset($mailer);
$mailer = new MailingHelper();
$result = $mailer->to('recipient@example.com')
                 ->from('sender@example.com', 'Sender Name')
                 ->subject('Test Email with Attachment')
                 ->message('<h1>Hello!</h1><p>This is a test email with an attachment.</p>')
                 ->attach('/path/to/file.pdf')
                 ->send();