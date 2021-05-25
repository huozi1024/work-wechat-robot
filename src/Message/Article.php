<?php

namespace Huozi\WorkWechat\Message;

class Article extends Message
{
    public $article;

    public function __construct($title = ' ', $description = ' ', $url = ' ', $picurl = ' ')
    {
        $this->type = 'news';
        $this->title($title);
        $this->description($description);
        $this->url($url);
        $this->picurl($picurl);
    }

    public function title($title)
    {
        $this->article['title'] = $title;
        return $this;
    }

    public function description($description)
    {
        $this->article['description'] = $description;
        return $this;
    }

    public function url($url)
    {
        $this->article['url'] = $url;
        return $this;
    }

    public function picurl($picurl)
    {
        $this->article['picurl'] = $picurl;
        return $this;
    }

    public function toArray()
    {
        return [
            'msgtype' => $this->type,
            $this->type => [
                'articles' => [
                    $this->article
                ]
            ]
        ];
    }
}
