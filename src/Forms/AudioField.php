<?php

namespace Forms;

class AudioField extends FileField
{
    protected $allowedExtensions = ['mp3', 'wav', 'ogg'];

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $file = $_FILES[$this->name];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (strpos($mimeType, 'audio/') !== 0) {
            $this->setError("The uploaded file is not a valid audio file.");
            return false;
        }

        return true;
    }
}