<?php
namespace Huozi\WorkWechat\Monolog\Formatter;

class MarkdownFormatter extends TextFormatter
{

    protected $format = <<<FORMAT
{channel}.{level_name}
>message:`{message}`
>context:`{context}`
FORMAT;
}