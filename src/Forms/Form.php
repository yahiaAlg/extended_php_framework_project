<?php

namespace Forms;

use Forms\Field;

class Form
{
    protected $fields = [];
    protected $data = [];
    protected $errors = [];

    public function __construct(array $fields = [])
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    public function isValid()
    {
        $this->errors = [];
        $valid = true;

        foreach ($this->fields as $name => $field) {
            if (isset($this->data[$name])) {
                $field->setValue($this->data[$name]);
            }

            if (!$field->isValid()) {
                $this->errors[$name] = $field->getErrors();
                $valid = false;
            }
        }

        return $valid;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        $data = [];
        foreach ($this->fields as $name => $field) {
            $data[$name] = $field->getValue();
        }
        return $data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function render()
    {
        $output = '<form method="post">';
        foreach ($this->fields as $field) {
            $output .= $field->render();
        }
        $output .= '<input type="submit" value="Submit">';
        $output .= '</form>';
        return $output;
    }
}