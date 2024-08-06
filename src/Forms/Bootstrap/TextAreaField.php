<?php

namespace Forms\Bootstrap;

class TextAreaField extends Field
{
    protected $rows;

    public function __construct($name, $label = '', $required = false, $rows = 3)
    {
        parent::__construct($name, $label, $required);
        $this->rows = $rows;
    }

    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $value = htmlspecialchars($this->value?? "");
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <textarea name='{$this->name}' id='{$this->name}' rows='{$this->rows}' {$attrs}>{$value}</textarea>
                {$this->renderErrors()}";
    }
}