<?php

namespace Huozi\WorkWechat;

class WorkWechatClient
{
    
    /**
     * @var string
     */
    protected $baseUri = 'https://qyapi.weixin.qq.com';

    public function __construct()
    {

    }

    public function request($uri, $method = 'GET', $options = [])
    {
        $response = $this->getClient()->request($method, $uri, $options);
        return $response->getBody();
    }

    protected function getClient()
    {
        return new \GuzzleHttp\Client([
            'base_uri' => $this->baseUri,
        ]);
    }
}