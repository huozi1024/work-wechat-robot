<?php

namespace Huozi\WorkWechat\Monolog\Formatter;

class TextFormatter extends \Monolog\Formatter\NormalizerFormatter
{

    /**
     * @var string
     */
    protected $messageFormat = "{channel}.{level_name}\n{message}\n{context}";

    /**
     * @param string $messageFormat
     * @param string $dateFormat
     */
    public function __construct($messageFormat = null, $dateFormat = null)
    {
        parent::__construct($dateFormat);
        $messageFormat AND $this->messageFormat = $messageFormat;
    }

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        return preg_replace_callback('/\{([\w\.]+)\}/', function ($match) use ($record) {
            $replase = arr_get($record, $match[1], '');
            $replase = $match[1] == 'datetime' ? $replase->format($this->dateFormat) : $replase;
            return (is_object($replase) || is_array($replase)) ? json_encode($replase, JSON_UNESCAPED_UNICODE) : $replase;
        }, $this->messageFormat);
    }

    /**
     * @param string $format
     */
    public function setMessageFormat($messageFormat)
    {
        $this->messageFormat = $messageFormat;
    }

}
