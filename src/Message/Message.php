<?php

namespace Huozi\WorkWechat\Message;

use Huozi\WorkWechat\WorkWechatRobot;

abstract class Message
{

    protected $type;

    protected $message = [];

    private $robot;

    public function __get($key)
    {
        return $this->message[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->message[$key] = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMessage()
    {
        return $this->mesage;
    }

    public function toArray()
    {
        return [
            'msgtype' => $this->type,
            $this->type => $this->message,
        ];
    }

    public function send($robotKey)
    {
        $this->robot[$robotKey] = $this->robot[$robotKey] ?? new WorkWechatRobot($robotKey);
        return $this->robot[$robotKey]->message($this->toArray());
    }
}
