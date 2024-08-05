<?php

namespace Forms\Bootstrap;

class DateTimeField extends Field
{
    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='datetime-local' name='{$this->name}' id='{$this->name}' value='{$value}' {$attrs}>
                {$this->renderErrors()}";
    }
}