<?php

namespace Forms;

class TextAreaField extends Field
{
    protected $rows;
    protected $cols;

    public function __construct($name, $label = '', $required = false, $rows = 3, $cols = 40)
    {
        parent::__construct($name, $label, $required);
        $this->rows = $rows;
        $this->cols = $cols;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value);
        return "<label for='{$this->name}'>{$this->label}</label>
                <textarea name='{$this->name}' id='{$this->name}' rows='{$this->rows}' cols='{$this->cols}' {$required}>{$value}</textarea>";
    }
}