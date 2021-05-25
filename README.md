# work-wechat-robot

企业微信机器人sdk

### 0.安装
>composer require huo-zi/work-wechat-robot
### 1.使用
#### 1.1 直接使用
> <pre>$robot = new WorkWechatRobot($robotKey);
> $robot->text($content); // 文本消息
> $robot->markdown($content); // markdown消息
> $robot->image($filename); // 图片消息 支持本地图片和网络图片
> $robot->news($title, $url, $desc, $picurl); // 图文消息
> $robot->file($filename); // 发送上传文件
> </pre>

#### 1.2 v2.1.0之后支持创建消息对象发送消息：
> <pre>
> $messsage = new Text();     // new Markdwon(); new Image()...
> $messsage->content('文本消息');
> $messsage->send($robotKey); // 或使用 $robot->message($messsage);
> </pre>

### 2.作为Monolog的通道使用
#### 2.1 配置通道
* laravel框架
在配置文件`logging.php`的`channels`数组中增加：
> <pre>'wxwork_robot' => [  
>     'driver' => 'monolog',  
>     'level' => 'notice',  
>     'handler' => \Huozi\WorkWechat\Monolog\Handler\RobotHandler::class,  
>     'handler_with' => [  
>         'robotKey' => 'your_wxwork_robot_key',  
>     ],  
> ],</pre>
然后修改`channels`节点`stack`的`channels`，增加`wxwork_robot`
详见[laravel高度自定义Monolog通道](https://learnku.com/docs/laravel/8.x/logging/9376#advanced-monolog-channel-customization)
* 其他框架
> <pre>$logger = new \Monolog\Logger($name);
> <b>$logger->pushHandler(new RobotHandler($robotKey));</b>
></pre>
#### 2.2 日志格式化
提供了`TextFormatter`和`MarkdownFormatter`格式化原始日志，输出方便阅读的内容
* laravel框架,修改`logging.php`：
> <pre>'wxwork_robot' => [
>     'driver' => 'monolog',
>     'level' => 'notice',
>     'handler' => \Huozi\WorkWechat\Monolog\Handler\RobotHandler::class,
>     'handler_with' => [
>         'robotKey' => 'your_wxwork_robot_key',
>     ],
>     <b>'formatter' => \Huozi\WorkWechat\Monolog\Formatter\MarkdownFormatter::class,</b>
> ],</pre>
`TextFormatter`和`MarkdownFormatter`都提供了默认的格式化结构，如果需要自定义可以：
> <pre>    'formatter' => \Huozi\WorkWechat\Monolog\Formatter\TextFormatter::class,
>     'formatter_with' => [
>         <b>'messageFormat' => '{level_name}:{message} \n {extra.file}:{extra.line}'</b>
>     ]
> </pre>
* 其他框架
> <pre>$messageFormat = '{level_name}:{message} \n {extra.file}:{extra.line}';
> <b>$formatter = new TextFormatter($messageFormat);</b>
> $logger->pushHandler((new RobotHandler($robotKey))<b>->setFormatter($formatter))</b>;
> </pre>
