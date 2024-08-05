<?php
// MaxLength.php

namespace Validation\Rules;

class MaxLength implements Rule
{
    private $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function validate($value): bool
    {
        return strlen($value) <= $this->maxLength;
    }

    public function getMessage(): string
    {
        return "The field must not exceed {$this->maxLength} characters.";
    }
}