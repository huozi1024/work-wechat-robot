<?php
namespace Huozi\WorkWechat\Monolog\Handler;

use Huozi\WorkWechat\WorkWechatRobot;
use Huozi\WorkWechat\Monolog\Formatter\MarkdownFormatter;

class RobotHandler extends \Monolog\Handler\AbstractProcessingHandler
{

    /**
     * @var WorkWechatRobot
     */
    private $robot;

    /**
     * @param string $robotKey
     * @param int|string $level
     * @param boolean $bubble
     */
    public function __construct($robotKey, $level = \Monolog\Logger::ALERT, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->robot = new WorkWechatRobot($robotKey);
    }

    /**
     * @inherits
     */
    protected function write(array $record): void
    {
        $formatter = $this->getFormatter();
        if ($formatter instanceof MarkdownFormatter) {
            $this->markdownMsg($record);
        } else {
            $this->textMsg($record);
        }
    }

    /**
     * 文本消息
     * 
     * @param array $record
     * @return void
     */
    protected function textMsg(array $record)
    {
        if (strlen($record['formatted']) > 5120) {
            $record['formatted'] = substr($record['formatted'], 0, 5117) . '...';
        }
        $this->robot->text($record['formatted']);
    }

    /**
     * markdown消息
     *
     * @param array $record
     * @return void
     */
    protected function markdownMsg(array $record)
    {
        $this->robot->markdown($record['formatted']);
    }
}