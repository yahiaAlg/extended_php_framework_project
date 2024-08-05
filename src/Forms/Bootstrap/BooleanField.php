<?php

namespace Forms\Bootstrap;

class BooleanField extends Field
{
    public function render()
    {
        $checked = $this->value ? 'checked' : '';
        $required = $this->required ? 'required' : '';
        return "<div class='form-check'>
                    <input type='checkbox' name='{$this->name}' id='{$this->name}' value='1' {$checked} {$required} class='form-check-input'>
                    <label class='form-check-label' for='{$this->name}'>{$this->label}</label>
                    {$this->renderErrors()}
                </div>";
    }
}