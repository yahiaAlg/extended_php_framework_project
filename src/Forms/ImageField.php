<?php

namespace Forms;

class ImageField extends FileField
{
    protected $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $file = $_FILES[$this->name];
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $this->setError("The uploaded file is not a valid image.");
            return false;
        }

        return true;
    }
}