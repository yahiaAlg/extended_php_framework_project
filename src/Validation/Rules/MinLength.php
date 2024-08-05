<?php
// MinLength.php

namespace Validation\Rules;

class MinLength implements Rule
{
    private $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    public function validate($value): bool
    {
        return strlen($value) >= $this->minLength;
    }

    public function getMessage(): string
    {
        return "The field must be at least {$this->minLength} characters.";
    }
}