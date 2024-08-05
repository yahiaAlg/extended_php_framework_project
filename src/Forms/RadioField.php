<?php

namespace Forms;

class RadioField extends Field
{
    protected $choices;

    public function __construct($name, $label = '', $required = false, array $choices = [])
    {
        parent::__construct($name, $label, $required);
        $this->choices = $choices;
    }

    public function render()
    {
        $output = "<fieldset><legend>{$this->label}</legend>";
        foreach ($this->choices as $value => $choiceLabel) {
            $checked = $this->value == $value ? 'checked' : '';
            $id = "{$this->name}_{$value}";
            $output .= "<div>
                <input type='radio' id='{$id}' name='{$this->name}' value='{$value}' {$checked}>
                <label for='{$id}'>{$choiceLabel}</label>
            </div>";
        }
        $output .= "</fieldset>";
        return $output;
    }

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!array_key_exists($this->value, $this->choices)) {
            $this->setError("Invalid choice selected.");
            return false;
        }

        return true;
    }
}