<?php
namespace Huozi\WorkWechat\Monolog\Handler;

use Huozi\WorkWechat\WorkWechatRobot;
use Huozi\WorkWechat\Monolog\Formatter\MarkdownFormatter;

class RobotHandler extends \Monolog\Handler\AbstractProcessingHandler
{

    private $robot;

    public function __construct($robotKey, $level = \Monolog\Logger::ALERT, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->robot = new WorkWechatRobot($robotKey);
    }

    protected function write(array $record)
    {
        $formatter = $this->getFormatter();
        if ($formatter instanceof MarkdownFormatter) {
            $this->markdownMsg($record);
        } else {
            $this->textMsg($record);
        }
    }

    /**
     *
     * @param WorkWechatRobot $robot
     * @param array $record
     */
    protected function textMsg(array $record)
    {
        if (strlen($record['formatted']) > 5120) {
            $record['formatted'] = substr($record['formatted'], 0, 5117) . '...';
        }
        $this->robot->text($record['formatted']);
    }

    /**
     *
     * @param WorkWechatRobot $robot
     * @param array $record
     */
    protected function markdownMsg(array $record)
    {
        $this->robot->markdown($record['formatted']);
    }
}