<?php

namespace Huozi\WorkWechat;

class WorkWechatClient
{

    /**
     * @var string
     */
    protected $baseUri = 'https://qyapi.weixin.qq.com';

    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    public function request($uri, $method = 'GET', $options = [])
    {
        $response = $this->client->request($method, $uri, $options);
        return $response->getBody();
    }

    public function getClient()
    {
        return $this->client;
    }
}
