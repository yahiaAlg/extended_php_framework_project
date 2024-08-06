<?php

namespace Forms;

class CharField extends Field
{
    protected $minLength;
    protected $maxLength;

    public function __construct($name, $label = '', $required = false, $minLength = null, $maxLength = null)
    {
        parent::__construct($name, $label, $required);
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $length = strlen($this->value);

        if ($this->minLength !== null && $length < $this->minLength) {
            $this->setError("This field must be at least {$this->minLength} characters long.");
            return false;
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            $this->setError("This field cannot be more than {$this->maxLength} characters long.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value?? "");
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='text' name='{$this->name}' id='{$this->name}' value='{$value}' {$required}>";
    }
}