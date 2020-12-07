<?php
namespace Huozi\WorkWechat;

class WorkWechatRobot
{

    private $robotUrl = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/';

    private $robotKey;

    public function __construct($key)
    {
        $this->robotKey = $key;
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
                'content' => content
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
            'msgtype' => 'msgtype',
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

    public function file($mediaId)
    {
        return $this->message([
            'msgtype' => 'file',
            'file' => [
                'media_id' => $mediaId
            ]
        ]);
    }

    private function message($message)
    {
        return $this->send('send', json_encode($message), [
            'Content-Type: application/json'
        ]);
    }

    private function upload($file)
    {
        return $this->send('upload_media', [
            'media' => $file
        ], [
            'Content-Type: multipart/form-data'
        ]);
    }

    private function send($type, $data, $headers)
    {
        $url = $this->robotUrl . $type . '?key=' . $this->robotKey . ($type == 'upload_media' ? '&type=file' : '');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        $res_code = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        if ($result === false || $res_code !== 200) {
            $message = sprintf('curl error: %s, errno: %d, response_code: %d', curl_error($curl), curl_errno($curl), $res_code);
            curl_close($curl);
            return $message; // ;
        }
        curl_close($curl);
        return substr($result, $header_size);
    }
}