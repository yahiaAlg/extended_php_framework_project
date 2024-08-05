<?php

namespace Forms\Bootstrap;

class AudioField extends FileField
{
    public function render()
    {
        $attrs = $this->getCommonAttributes();
        return "<label for='{$this->name}' class='form-label'>{$this->label}</label>
                <input type='file' name='{$this->name}' id='{$this->name}' accept='audio/*' {$attrs}>
                {$this->renderErrors()}";
    }
}