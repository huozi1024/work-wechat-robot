<?php

namespace Huozi\WorkWechat\Message;

class Article extends Message
{

    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $picurl
     */
    public function __construct($title = ' ', $description = ' ', $url = ' ', $picurl = ' ')
    {
        $this->type = 'news';
        $this->articles[] = [];
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
        $this->articles[0]['title'] = $title;
        return $this;
    }

    /**
     * Set article description
     *
     * @param string $description
     */
    public function description($description)
    {
        $this->articles[0]['description'] = $description;
        return $this;
    }

    /**
     * Set article url 
     *
     * @param string $url
     */
    public function url($url)
    {
        $this->articles[0]['url'] = $url;
        return $this;
    }

    /**
     * Set article picurl
     *
     * @param string $picurl
     */
    public function picurl($picurl)
    {
        $this->articles[0]['picurl'] = $picurl;
        return $this;
    }

    /**
     * Get articles
     *
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
