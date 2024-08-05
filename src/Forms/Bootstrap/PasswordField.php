<?php

namespace Forms\Bootstrap;

class PasswordField extends Field
{
    public function render()
    {
        $attrs = $this->getCommonAttributes();
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='password' name='{$this->name}' id='{$this->name}' {$attrs}>
                {$this->renderErrors()}";
    }
}