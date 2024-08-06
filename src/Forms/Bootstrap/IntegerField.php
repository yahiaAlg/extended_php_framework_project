<?php

namespace Forms\Bootstrap;

class IntegerField extends Field
{
    protected $min;
    protected $max;

    public function __construct($name, $label = '', $required = false, $min = null, $max = null)
    {
        parent::__construct($name, $label, $required);
        $this->min = $min;
        $this->max = $max;
    }

    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $min = $this->min !== null ? "min='{$this->min}'" : '';
        $max = $this->max !== null ? "max='{$this->max}'" : '';
        $value = htmlspecialchars($this->value?? "");
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='number' name='{$this->name}' id='{$this->name}' value='{$value}' {$attrs} {$min} {$max} step='1'>
                {$this->renderErrors()}";
    }
}