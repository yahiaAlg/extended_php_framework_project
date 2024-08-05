<?php

namespace Forms;

class FileField extends Field
{
    protected $allowedExtensions;
    protected $maxSize;

    public function __construct($name, $label = '', $required = false, array $allowedExtensions = [], $maxSize = 2097152)
    {
        parent::__construct($name, $label, $required);
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSize = $maxSize; // Default to 2MB
    }

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        if (!isset($_FILES[$this->name])) {
            $this->setError("No file was uploaded.");
            return false;
        }

        $file = $_FILES[$this->name];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->setError("File upload failed.");
            return false;
        }

        if ($file['size'] > $this->maxSize) {
            $this->setError("File size exceeds the maximum limit.");
            return false;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!empty($this->allowedExtensions) && !in_array($ext, $this->allowedExtensions)) {
            $this->setError("File type not allowed.");
            return false;
        }

        return true;
    }

    public function render()
    {
        $required = $this->required ? 'required' : '';
        return "<label for='{$this->name}'>{$this->label}</label>
                <input type='file' name='{$this->name}' id='{$this->name}' {$required}>";
    }
}