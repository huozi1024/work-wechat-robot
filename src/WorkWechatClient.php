<?php

namespace Huozi\WorkWechat;

class WorkWechatClient
{

    /**
     * @var string
     */
    protected $baseUri = 'https://qyapi.weixin.qq.com';

    /**
     *  @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * Send request
     *
     * @param string    $uri     URI object or string.
     * @param string    $method  HTTP method.
     * @param array     $options Request options to apply.
     * 
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($uri, $method = 'GET', $options = [])
    {
        return $this->client->request($method, $uri, $options);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
