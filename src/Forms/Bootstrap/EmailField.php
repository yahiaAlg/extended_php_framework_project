<?php

namespace Forms\Bootstrap;

class EmailField extends Field
{
    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='email' name='{$this->name}' id='{$this->name}' value='{$value}' {$attrs}>
                {$this->renderErrors()}";
    }
}