<?php

namespace Huozi\WorkWechat\Message;

class Text extends Message
{

    /**
     * @param string $content
     * @param array $mentions 
     * @param array $mobiles
     */
    public function __construct($content = '', $mentions = [], $mobiles = [])
    {
        $this->type = 'text';
        $this->content($content)->mention($mentions)->mentionMobile($mobiles);
    }

    /**
     * @param string $content
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param string|array $mentions
     */
    public function mention($mentions)
    {
        $mentions = is_array($mentions) ? $mentions : [$mentions];
        $this->mentioned_list = array_merge($this->mentioned_list ?? [], $mentions);
        return $this;
    }

    /**
     * @param string|array $mobiles
     */
    public function mentionMobile($mobiles)
    {
        $mobiles = is_array($mobiles) ? $mobiles : [$mobiles];
        $this->mentioned_mobile_list = array_merge($this->mentioned_mobile_list ?? [], $mobiles);
        return $this;
    }
}
