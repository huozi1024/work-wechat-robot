<?php

namespace Huozi\WorkWechat\Message;

class News extends Message
{

    /**
     * @param array $articles
     */
    public function __construct($articles = [])
    {
        $this->type = 'news';
        $this->articles = $articles;
    }

    /**
     * Append one article
     *
     * @param array $article
     */
    public function article($article)
    {
        $articles = $article instanceof Article ? $article->getArticles() : [$article];
        $this->articles = array_merge($this->articles ?? [], $articles);
        return $this;
    }
}
