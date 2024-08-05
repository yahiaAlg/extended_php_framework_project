<?php

namespace Forms;

class ChoiceField extends Field
{
    protected $choices;

    public function __construct($name, $label = '', $required = false, array $choices = [])
    {
        parent::__construct($name, $label, $required);
        $this->choices = $choices;
    }

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!in_array($this->value, array_keys($this->choices))) {
            $this->setError("Invalid choice selected.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $output = "<label for='{$this->name}'>{$this->label}</label>";
        $output .= "<select name='{$this->name}' id='{$this->name}'>";
        foreach ($this->choices as $value => $label) {
            $selected = $this->value == $value ? 'selected' : '';
            $output .= "<option value='{$value}' {$selected}>{$label}</option>";
        }
        $output .= "</select>";
        return $output;
    }
}