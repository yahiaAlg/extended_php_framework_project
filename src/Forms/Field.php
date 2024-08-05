<?php

namespace Forms;

abstract class Field
{
    protected $name;
    protected $label;
    protected $value;
    protected $required;
    protected $errors = [];

    public function __construct($name, $label = '', $required = false)
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst($name);
        $this->required = $required;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setError($error)
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        $this->errors = [];
        if ($this->required && empty($this->value)) {
            $this->setError("This field is required.");
            return false;
        }
        return true;
    }

    abstract public function render();
}