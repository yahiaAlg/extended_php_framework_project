<?php

namespace Forms\Bootstrap;

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
        $output = "<fieldset class='mb-3'><legend class='form-label'>{$this->label}</legend>";
        foreach ($this->choices as $value => $choiceLabel) {
            $checked = $this->value == $value ? 'checked' : '';
            $id = "{$this->name}_{$value}";
            $output .= "<div class='form-check'>
                <input class='form-check-input' type='radio' id='{$id}' name='{$this->name}' value='{$value}' {$checked}>
                <label class='form-check-label' for='{$id}'>{$choiceLabel}</label>
            </div>";
        }
        $output .= $this->renderErrors();
        $output .= "</fieldset>";
        return $output;
    }
}