<?php

namespace Huozi\WorkWechat\Message;

class File extends Message
{

    /**
     * @param string $mediaId
     */
    public function __construct($mediaId)
    {
        $this->type = 'file';
        $this->media($mediaId);
    }

    /**
     * Set media_id
     *
     * @param string $mediaId
     */
    public function media($mediaId)
    {
        $this->media_id = $mediaId;
        return $this;
    }
}
