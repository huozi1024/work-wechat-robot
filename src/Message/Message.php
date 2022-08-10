<?php

namespace Huozi\WorkWechat\Message;

use Huozi\WorkWechat\WorkWechatRobot;

abstract class Message
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $message = [];

    private $robot;

    /**
     * Get message type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get message
     */
    public function getMessage()
    {
        return $this->mesage;
    }

    /**
     * Build message content
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'msgtype' => $this->type,
            $this->type => $this->message,
        ];
    }

    /**
     * Send this mesage by robotKey
     *
     * @param string $robotKey
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($robotKey)
    {
        $this->robot[$robotKey] = $this->robot[$robotKey] ?? new WorkWechatRobot($robotKey);
        return $this->robot[$robotKey]->message($this->toArray());
    }

    public function __get($key)
    {
        return $this->message[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->message[$key] = $value;
    }

}
