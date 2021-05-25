<?php

namespace Huozi\WorkWechat\Message;

class Image extends Message
{

    public function __construct($filename = null)
    {
        $this->type = 'image';
        $filename AND $this->file($filename);
    }

    public function file($filename)
    {
        $this->base64 = base64_encode(file_get_contents($filename));
        $this->md5 = md5_file($filename);
        return $this;
    }
}
