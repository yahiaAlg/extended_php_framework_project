<?php

namespace Helpers;

class MailingHelper
{
    private $to;
    private $subject;
    private $message;
    private $headers = [];
    private $attachments = [];

    public function __construct()
    {
        $this->headers[] = 'MIME-Version: 1.0';
        $this->headers[] = 'Content-type: text/html; charset=utf-8';
    }

    public function to($email)
    {
        $this->to = $email;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function from($email, $name = '')
    {
        $this->headers[] = 'From: ' . ($name ? "$name <$email>" : $email);
        return $this;
    }

    public function cc($email)
    {
        $this->headers[] = 'Cc: ' . $email;
        return $this;
    }

    public function bcc($email)
    {
        $this->headers[] = 'Bcc: ' . $email;
        return $this;
    }

    public function replyTo($email)
    {
        $this->headers[] = 'Reply-To: ' . $email;
        return $this;
    }

    public function attach($filePath)
    {
        $this->attachments[] = $filePath;
        return $this;
    }

    public function send()
    {
        if (empty($this->to) || empty($this->subject) || empty($this->message)) {
            throw new \Exception('To, subject, and message are required.');
        }

        $headers = implode("\r\n", $this->headers);

        if (!empty($this->attachments)) {
            $boundary = md5(time());
            $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"";

            $message = "--$boundary\r\n" .
                       "Content-Type: text/html; charset=utf-8\r\n" .
                       "Content-Transfer-Encoding: base64\r\n\r\n" .
                       chunk_split(base64_encode($this->message)) . "\r\n";

            foreach ($this->attachments as $attachment) {
                if (file_exists($attachment)) {
                    $content = file_get_contents($attachment);
                    $message .= "--$boundary\r\n" .
                                "Content-Type: application/octet-stream; name=\"" . basename($attachment) . "\"\r\n" .
                                "Content-Transfer-Encoding: base64\r\n" .
                                "Content-Disposition: attachment\r\n\r\n" .
                                chunk_split(base64_encode($content)) . "\r\n";
                }
            }

            $message .= "--$boundary--";
        } else {
            $message = $this->message;
        }

        return mail($this->to, $this->subject, $message, $headers);
    }
}