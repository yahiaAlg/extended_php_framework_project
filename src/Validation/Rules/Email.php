<?php
// Email.php

namespace Validation\Rules;

class Email implements Rule
{
    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getMessage(): string
    {
        return "The field must be a valid email address.";
    }
}