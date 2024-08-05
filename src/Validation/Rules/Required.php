<?php
// Required.php

namespace Validation\Rules;

class Required implements Rule
{
    public function validate($value): bool
    {
        return !empty($value);
    }

    public function getMessage(): string
    {
        return "The field is required.";
    }
}