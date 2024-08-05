<?php

namespace Forms;

class BooleanField extends Field
{
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!is_bool($this->value) && $this->value !== '0' && $this->value !== '1') {
            $this->setError("Please provide a valid boolean value.");
            return false;
        }

        return true;
    }

    public function setValue($value)
    {
        if ($value === '0' || $value === '1') {
            $this->value = (bool)$value;
        } else {
            $this->value = $value;
        }
    }

    public function render()
    {
        $checked = $this->value ? 'checked' : '';
        $required = $this->required ? 'required' : '';
        return "<div class='checkbox'>
                    <label>
                        <input type='checkbox' name='{$this->name}' id='{$this->name}' value='1' {$checked} {$required}>
                        {$this->label}
                    </label>
                </div>";
    }
}