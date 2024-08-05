<?php

namespace Forms;

class VideoField extends FileField
{
    protected $allowedExtensions = ['mp4', 'webm', 'ogg'];

    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $file = $_FILES[$this->name];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (strpos($mimeType, 'video/') !== 0) {
            $this->setError("The uploaded file is not a valid video.");
            return false;
        }

        return true;
    }
}