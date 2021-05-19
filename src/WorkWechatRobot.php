<?php
namespace Huozi\WorkWechat;

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
        $text = array();
        if (strpos($content, '@') !== false) {
            preg_match_all('/(\@(\d{11}|所有人))/u', $content, $ats);
            if ($ats[1]) {
                $content = str_replace($ats[1], '', $content);
                foreach ($ats[2] as $at) {
                    if ($at == '所有人') {
                        $text['mentioned_list'][] = '@all';
                    } else {
                        $text['mentioned_mobile_list'][] = $at;
                    }
                }
            }
        }
        $text['content'] = $content;
        return $this->message([
            'msgtype' => 'text',
            'text' => $text
        ]);
    }

    public function markdown($content)
    {
        return $this->message([
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => $content
            ]
        ]);
    }

    public function news($title, $url, $desc = null, $picurl = null)
    {
        $article = [
            'title' => $title,
            'description' => $desc,
            'url' => $url,
            'picurl' => $picurl
        ];

        return $this->message([
            'msgtype' => 'news',
            'news' => [
                'articles' => [
                    $article
                ]
            ]
        ]);
    }

    public function image($imageFile)
    {
        return $this->message([
            'msgtype' => 'image',
            'image' => [
                'base64' => base64_encode(file_get_contents($imageFile)),
                'md5' => md5_file($imageFile)
            ]
        ]);
    }

    public function file($file)
    {
        $response = $this->upload($file);
        if (!($result = json_decode($response)) && $result->errcode <> '0') {
            return $response;
        }
        return $this->message([
            'msgtype' => 'file',
            'file' => [
                'media_id' => $result->media_id
            ]
        ]);
    }

    public function message($message)
    {
        return $this->client->request('cgi-bin/webhook/send?key=' . $this->robotKey,'POST', [
            'json' => $message
        ]);
    }

    public function upload($file)
    {
        return $this->client->request('cgi-bin/webhook/upload_media?type=file&key=' . $this->robotKey,'POST', [
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