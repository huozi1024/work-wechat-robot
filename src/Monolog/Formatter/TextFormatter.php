<?php
namespace Huozi\WorkWechat\Monolog\Formatter;

use Huozi\WorkWechat\WorkWechatRobot;
use Monolog\Formatter\NormalizerFormatter;

class TextFormatter extends \Monolog\Formatter\NormalizerFormatter
{

    protected $format = "{channel}.{level_name}\n{message}\n{context}\n{extra.url}";

    public function __construct($messageFormat = null, $dateFormat = null)
    {
        parent::__construct($dateFormat);
        $messageFormat AND $this->format = $messageFormat;
    }

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $format = preg_replace_callback('/\{([\w\.]+)\}/', function($match) use ($record) {
            $replase = static::arrayGet($record, $match[1], '');
            $replase = $match[1] == 'datetime' ? $replase->format($this->dateFormat) : $replase;
            return (is_object($replase) || is_array($replase)) ? json_encode($replase, JSON_UNESCAPED_UNICODE) : $replase;
        }, $this->format);
        return $format;
    }

    private static function arrayGet($array, $key, $default = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }
}
