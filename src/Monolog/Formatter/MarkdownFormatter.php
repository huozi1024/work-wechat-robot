<?php
namespace Huozi\WorkWechat\Monolog\Formatter;

use Huozi\WorkWechat\WorkWechatRobot;

class MarkdownFormatter extends TextFormatter
{

    protected $format = <<<FORMAT
{channel}.{level_name}
>message:`{message}`
>context:`{context}`
>url:`{extra.url}`
FORMAT;
}