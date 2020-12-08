<?php
namespace Huozi\WorkWechat\Monolog\Handler;

use Huozi\WorkWechat\WorkWechatRobot;

class RobotHandler extends \Monolog\Handler\AbstractProcessingHandler
{

    private $robot;

    private $msgType;

    public function __construct($robotKey, $msgType = 'text', $level = \Monolog\Logger::ALERT, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->robot[$robotKey] = $this->robot[$robotKey] ?? new WorkWechatRobot($robotKey);
        $this->msgType[$robotKey] = $msgType;
    }

    protected function write(array $record)
    {
        array_walk($this->robot, function ($robot, $robotKey) use ($record) {
            if (method_exists($this, $this->msgType[$robotKey] . 'Msg')) {
                $this->{$this->msgType[$robotKey] . 'Msg'}($robot, $record);
            } else {
                $robot->textMsg($robot, $record);
            }
        });
    }

    /**
     *
     * @param WorkWechatRobot $robot
     * @param array $record
     */
    protected function textMsg($robot, array $record)
    {
        $content = $record['channel'] . '.' . $record['level_name'] . PHP_EOL . $record['message'] . PHP_EOL . json_encode($record['context'], JSON_UNESCAPED_UNICODE);
        if (isset($record['extra']['url'])) {
            $content .= PHP_EOL . "from: {$record['extra']['url']}";
        }
        $robot->text($content);
    }

    /**
     *
     * @param WorkWechatRobot $robot
     * @param array $record
     */
    protected function markdownMsg($robot, array $record)
    {
        $record['context'] = json_encode($record['context'], JSON_UNESCAPED_UNICODE);
        $content = $record['channel'] . '.' . $record['level_name'] . PHP_EOL;
        $content .= ">message:`{$record['message']}`" . PHP_EOL;
        $content .= ">context:`{$record['context']}`" . PHP_EOL;
        if (isset($record['extra']['url'])) {
            $content .= ">url:`{$record['extra']['url']}`";
        }
        $robot->markdown($content);
    }
}