<?php

namespace Huozi\WorkWechat\Message;

class Article extends Message
{

    /**
     * @var array
     */
    private $article;

    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $picurl
     */
    public function __construct($title = ' ', $description = ' ', $url = ' ', $picurl = ' ')
    {
        $this->type = 'news';
        $this->title($title);
        $this->description($description);
        $this->url($url);
        $this->picurl($picurl);
    }

    /**
     * Set article title
     *
     * @param string $title
     */
    public function title($title)
    {
        $this->article['title'] = $title;
        return $this;
    }

    /**
     * Set article description
     *
     * @param string $description
     */
    public function description($description)
    {
        $this->article['description'] = $description;
        return $this;
    }

    /**
     * Set article url 
     *
     * @param string $url
     */
    public function url($url)
    {
        $this->article['url'] = $url;
        return $this;
    }

    /**
     * Set article picurl
     *
     * @param string $picurl
     */
    public function picurl($picurl)
    {
        $this->article['picurl'] = $picurl;
        return $this;
    }

    /**
     * Get articles
     *
     * @return array
     */
    public function getArticle()
    {
        return $this->article;
    }

    public function toArray()
    {
        return [
            'msgtype' => $this->type,
            $this->type => [
                'articles' => [$this->article]
            ],
        ];
    }
}
