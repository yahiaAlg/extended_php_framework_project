<?php

namespace Forms;

class DateField extends Field
{
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $this->value);
        if (!$date || $date->format('Y-m-d') !== $this->value) {
            $this->setError("Please enter a valid date in YYYY-MM-DD format.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='date' name='{$this->name}' id='{$this->name}' value='{$value}' {$required}>";
    }
}