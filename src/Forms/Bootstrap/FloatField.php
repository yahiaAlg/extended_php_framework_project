<?php

namespace Forms\Bootstrap;

class FloatField extends Field
{
    protected $min;
    protected $max;
    protected $step;

    public function __construct($name, $label = '', $required = false, $min = null, $max = null, $step = 'any')
    {
        parent::__construct($name, $label, $required);
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }

    public function render()
    {
        $attrs = $this->getCommonAttributes();
        $min = $this->min !== null ? "min='{$this->min}'" : '';
        $max = $this->max !== null ? "max='{$this->max}'" : '';
        $step = $this->step !== null ? "step='{$this->step}'" : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='number' name='{$this->name}' id='{$this->name}' value='{$value}' {$attrs} {$min} {$max} {$step}>
                {$this->renderErrors()}";
    }
}