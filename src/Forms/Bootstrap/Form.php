<?php

namespace Forms\Bootstrap;

class Form extends \Forms\Form
{
    public function render()
    {
        $output = '<form method="post" class="needs-validation" novalidate>';
        foreach ($this->fields as $field) {
            $output .= '<div class="mb-3">';
            $output .= $field->render();
            $output .= '</div>';
        }
        $output .= '<button type="submit" class="btn btn-primary">Submit</button>';
        $output .= '</form>';
        return $output;
    }
}