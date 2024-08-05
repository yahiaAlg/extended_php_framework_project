<?php
// Numeric.php

namespace Validation\Rules;

class Numeric implements Rule
{
    public function validate($value): bool
    {
        return is_numeric($value);
    }

    public function getMessage(): string
    {
        return "The field must be a numeric value.";
    }
}