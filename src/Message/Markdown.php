<?php

namespace Huozi\WorkWechat\Message;

class Markdown extends Text
{
    public function __construct($content = '')
    {
        parent::content($content);
        $this->type = 'markdown';
    }

}