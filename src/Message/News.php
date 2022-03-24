<?php

namespace Huozi\WorkWechat\Message;

class News extends Message
{

    public function __construct($articles = [])
    {
        $this->type = 'news';
        $this->articles = $articles;
    }

    public function article($article)
    {
        $article = $article instanceof Article ? $article->article : $article;
        $this->articles = array_merge($this->articles ?? [], [$article]);
        return $this;
    }
}
