<?php

namespace Forms;

class IntegerField extends Field
{
    protected $min;
    protected $max;

    public function __construct($name, $label = '', $required = false, $min = null, $max = null)
    {
        parent::__construct($name, $label, $required);
        $this->min = $min;
        $this->max = $max;
    }

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!is_numeric($this->value) || intval($this->value) != $this->value) {
            $this->setError("Please enter a valid integer.");
            return false;
        }

        $value = intval($this->value);

        if ($this->min !== null && $value < $this->min) {
            $this->setError("Value must be at least {$this->min}.");
            return false;
        }

        if ($this->max !== null && $value > $this->max) {
            $this->setError("Value must not exceed {$this->max}.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $min = $this->min !== null ? "min='{$this->min}'" : '';
        $max = $this->max !== null ? "max='{$this->max}'" : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='number' name='{$this->name}' id='{$this->name}' value='{$value}' {$required} {$min} {$max}>";
    }
}