<?php

namespace Forms;

class EmailField extends CharField
{
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->setError("Please enter a valid email address.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='email' name='{$this->name}' id='{$this->name}' value='{$value}' {$required}>";
    }
}