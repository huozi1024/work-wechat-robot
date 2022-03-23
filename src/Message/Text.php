<?php

namespace Huozi\WorkWechat\Message;

class Text extends Message
{

    public function __construct($content = '', $mentions = [], $mobiles = [])
    {
        $this->type = 'text';
        $this->content($content)->mention($mentions)->mentionMobile($mobiles);
    }

    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function mention($mentions)
    {
        $mentions = is_array($mentions) ? $mentions : [$mentions];
        $this->mentioned_list = array_merge($this->mentioned_list ?? [], $mentions);
        return $this;
    }

    public function mentionMobile($mobiles)
    {
        $mobiles = is_array($mobiles) ? $mobiles : [$mobiles];
        $this->mentioned_mobile_list = array_merge($this->mentioned_mobile_list ?? [], $mobiles);
        return $this;
    }
}
