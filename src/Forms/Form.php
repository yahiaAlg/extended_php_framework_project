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

    public function setData($data)
    {
        foreach ($this->fields as $name => $field) {
            if ($field instanceof FileField) {
                if (isset($_FILES[$name])) {
                    $field->setValue($_FILES[$name]);
                }
            } elseif (isset($data[$name])) {
                $field->setValue($data[$name]);
            }
        }
    }
    public function getField($fieldName)
    {
        return $this->fields[$fieldName] ?? null;
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
        $output = '<form method="post" enctype="multipart/form-data">';
        foreach ($this->fields as $name => $field) {
            $output .= $field->render();
            if (isset($this->errors[$name])) {
                foreach ($this->errors[$name] as $error) {
                    $output .= '<p class="error">' . htmlspecialchars($error) . '</p>';
                }
            }
        }
        $output .= '<input type="submit" value="Submit">';
        $output .= '</form>';
        return $output;
    }
}