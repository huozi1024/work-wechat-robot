<?php 
namespace Huozi\WorkWechat\Monolog\Handler;

use Monolog\Handler\AbstractHandler;
use Huozi\WorkWechat\WorkWechatRobot;

class RobotHandler extends \Monolog\Handler\AbstractProcessingHandler
{
    /**
     * @var WorkWechatRobot
     */
    private $robot;

    /**
     * @var string
     */
    private $robotKey;

    public function __construct($robotKey, $level = \Monolog\Logger::ALERT, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->robot[$robotKey] = $this->robot[$robotKey] ?? new WorkWechatRobot($robotKey);
        $this->robotKey = $robotKey;
    }

    protected function write(array $record)
    {
        $content = $record['channel'] . '.' . $record['level_name'] . PHP_EOL . $record['message'] . PHP_EOL . json_encode($record['context'], JSON_UNESCAPED_UNICODE);
        $this->robot[$this->robotKey]->text($content);
    }
}