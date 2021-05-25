<?php
namespace Huozi\WorkWechat;

use Huozi\WorkWechat\Message\Article;
use Huozi\WorkWechat\Message\File;
use Huozi\WorkWechat\Message\Image;
use Huozi\WorkWechat\Message\Markdown;
use Huozi\WorkWechat\Message\Message;
use Huozi\WorkWechat\Message\Text;

class WorkWechatRobot
{

    /**
     * @var WorkWechatClient
     */
    private $client;

    private $robotKey;

    public function __construct($key)
    {
        $this->robotKey = $key;
        $this->client = new WorkWechatClient();
    }

    public function setRobotKey($key)
    {
        $this->robotKey = $key;
    }

    public function text($content)
    {
        $text = new Text();
        if (strpos($content, '@') !== false) {
            preg_match_all('/(\@(\d{11}|所有人))/u', $content, $ats);
            if ($ats[1]) {
                $content = str_replace($ats[1], '', $content);
                foreach ($ats[2] as $at) {
                    if ($at == '所有人') {
                        $text->mention('@all');
                    } else {
                        $text->mentionMobile($at);
                    }
                }
            }
        }
        $text->content($content);
        return $this->message($text);
    }

    public function markdown($content)
    {
        return $this->message(new Markdown($content));
    }

    public function news($title, $url, $desc = null, $picurl = null)
    {
        return $this->message(new Article($title, $desc, $url, $picurl));
    }

    public function image($imageFile)
    {
        return $this->message(new Image($imageFile));
    }

    public function file($file)
    {
        $response = $this->upload($file);
        if (!($result = json_decode($response)) && $result->errcode <> '0') {
            return $response;
        }
        return $this->message(new File($result->media_id));
    }

    public function message($message)
    {
        return $this->client->request('cgi-bin/webhook/send?key=' . $this->robotKey, 'POST', [
            'json' => $message instanceof Message ? $message->toArray() : $message
        ]);
    }

    public function upload($file)
    {
        return $this->client->request('cgi-bin/webhook/upload_media?type=file&key=' . $this->robotKey, 'POST', [
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' => fopen($file, 'r'),
                    'file_name' => basename($file)
                ]
            ]
        ]);
    }
}