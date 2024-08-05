<?php

namespace Forms;

class DateTimeField extends Field
{
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $this->value);
        if (!$dateTime || $dateTime->format('Y-m-d\TH:i') !== $this->value) {
            $this->setError("Please enter a valid date and time in YYYY-MM-DDTHH:MM format.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='datetime-local' name='{$this->name}' id='{$this->name}' value='{$value}' {$required}>";
    }
}