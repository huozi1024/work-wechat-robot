<?php

namespace Huozi\WorkWechat\Message;

class File extends Message
{

    public function __construct($mediaId)
    {
        $this->type = 'file';
        $this->media($mediaId);
    }

    public function media($mediaId)
    {
        $this->media_id = $mediaId;
        return $this;
    }
}
