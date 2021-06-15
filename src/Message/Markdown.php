<?php

namespace Huozi\WorkWechat\Message;

class Markdown extends Text
{
    public function __construct($content = '')
    {
        parent::content(addslashes($content));
        $this->type = 'markdown';
    }

}