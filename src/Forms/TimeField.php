<?php

namespace Forms;

class TimeField extends Field
{
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $time = \DateTime::createFromFormat('H:i', $this->value);
        if (!$time || $time->format('H:i') !== $this->value) {
            $this->setError("Please enter a valid time in HH:MM format.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value?? "");
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='time' name='{$this->name}' id='{$this->name}' value='{$value}' {$required}>";
    }
}