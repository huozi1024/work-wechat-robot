<?php

namespace Huozi\WorkWechat\Message;

class Markdown extends Message
{

    public function __construct($content = '')
    {
        $this->type = 'markdown';
        $this->content = \addslashes($content);
    }
}
