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

    /**
     * @var string
     */
    private $robotKey;

    public function __construct($key)
    {
        $this->robotKey = $key;
        $this->client = new WorkWechatClient();
    }

    /**
     * Set robot key
     *
     * @param string $key
     * @return void
     */
    public function setRobotKey($key)
    {
        $this->robotKey = $key;
    }

    /**
     * Send text messsage
     *
     * @param string $content
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function text($content)
    {
        $text = new Text();
        if (\strpos($content, '@') !== false) {
            if (\preg_match_all('/\@([\w\-\.]+|所有人)/', $content, $ats) && $ats[0]) {
                $content = \str_replace($ats[0], '', $content);
                foreach ($ats[1] as $at) {
                    if ($at == '所有人') {
                        $text->mention('@all');
                    } elseif (\preg_match('/^\d{11}$/', $at)) {
                        $text->mentionMobile($at);
                    } else {
                        $text->mention($at);
                    }
                }
            }
        }
        $text->content($content);
        return $this->message($text);
    }

    /**
     * Send markdown messsage
     *
     * @param string $content
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markdown($content)
    {
        return $this->message(new Markdown($content));
    }

    /**
     * Send one news messsage
     *
     * @param string $title
     * @param string $url
     * @param string $desc
     * @param string $picurl
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function news($title, $url, $desc = null, $picurl = null)
    {
        return $this->message(new Article($title, $desc, $url, $picurl));
    }

    /**
     * Send image messsage
     *
     * @param string $imageFile 
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function image($imageFile)
    {
        return $this->message(new Image($imageFile));
    }

    /**
     * Send file messsage
     *
     * @param string $file 
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function file($file)
    {
        $response = $this->upload($file);
        if (($result = \json_decode($response->getBody())) && $result->errcode == 0) {
            return $this->message(new File($result->media_id));
        }
        return $response;
    }

    /**
     * Send message by robot
     *
     * @param Message|array $message
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function message($message)
    {
        return $this->client->request('cgi-bin/webhook/send?key=' . $this->robotKey, 'POST', [
            'json' => $message instanceof Message ? $message->toArray() : $message
        ]);
    }

    /**
     * Upload file
     *
     * @param string $file 
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($file)
    {
        return $this->client->request('cgi-bin/webhook/upload_media?type=file&key=' . $this->robotKey, 'POST', [
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' => \fopen($file, 'r'),
                    'file_name' => \basename($file)
                ]
            ]
        ]);
    }
}
