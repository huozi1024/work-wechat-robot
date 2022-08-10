<?php
namespace Huozi\WorkWechat\Monolog\Formatter;

class MarkdownFormatter extends TextFormatter
{

    protected $messageFormat = <<<FORMAT
{channel}.{level_name}
>message:`{message}`
>context:`{context}`
FORMAT;
}