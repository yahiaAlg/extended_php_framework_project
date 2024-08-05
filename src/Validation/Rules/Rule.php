<?php
// Rule.php

namespace Validation\Rules;

interface Rule
{
    public function validate($value): bool;
    public function getMessage(): string;
}