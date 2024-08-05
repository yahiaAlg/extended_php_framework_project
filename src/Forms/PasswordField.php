<?php

namespace Forms;

class PasswordField extends CharField
{
    public function render()
    {
        $required = $this->required ? 'required' : '';
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='password' name='{$this->name}' id='{$this->name}' {$required}>";
    }
}