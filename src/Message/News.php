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

        foreach ($articles as $article) {
            $this->article($article);
        }
    }

    /**
     * Append one article
     *
     * @param array $article
     */
    public function article($article)
    {
        $article = $article instanceof Article ? $article->getArticle() : $article;
        $this->articles = \array_merge($this->articles ?? [], [$article]);
        return $this;
    }
}
