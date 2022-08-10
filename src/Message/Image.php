<?php

namespace Huozi\WorkWechat\Message;

class Image extends Message
{

    /**
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        $this->type = 'image';
        $filename AND $this->file($filename);
    }

    /**
     * Set file
     *
     * @param string $filename
     */
    public function file($filename)
    {
        $this->base64 = base64_encode(file_get_contents($filename));
        $this->md5 = md5_file($filename);
        return $this;
    }
}
