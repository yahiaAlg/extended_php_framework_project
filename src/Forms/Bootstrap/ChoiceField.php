<?php

namespace Forms\Bootstrap;

class ChoiceField extends Field
{
    protected $choices;

    public function __construct($name, $label = '', $required = false, array $choices = [])
    {
        parent::__construct($name, $label, $required);
        $this->choices = $choices;
    }

    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $output = "<label for='{$this->name}' class='form-label'>{$this->label}</label>";
        $output .= "<select name='{$this->name}' id='{$this->name}' {$attrs}>";
        foreach ($this->choices as $value => $label) {
            $selected = $this->value == $value ? 'selected' : '';
            $output .= "<option value='{$value}' {$selected}>{$label}</option>";
        }
        $output .= "</select>";
        $output .= $this->renderErrors();
        return $output;
    }
}