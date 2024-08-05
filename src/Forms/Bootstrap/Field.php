<?php

namespace Forms\Bootstrap;

abstract class Field extends \Forms\Field
{
    protected function renderErrors()
    {
        if (empty($this->errors)) {
            return '';
        }

        $output = '<div class="invalid-feedback">';
        foreach ($this->errors as $error) {
            $output .= "<div>{$error}</div>";
        }
        $output .= '</div>';

        return $output;
    }

    protected function getCommonAttributes()
    {
        $required = $this->required ? 'required' : '';
        $isInvalid = !empty($this->errors) ? 'is-invalid' : '';
        return "class='form-control {$isInvalid}' {$required}";
    }
}