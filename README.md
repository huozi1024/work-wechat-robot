# work-wechat-robot

企业微信机器人sdk

### 0.安装
```
composer require huo-zi/work-wechat-robot
```
php < 8 || laravel < 9
```
composer require huo-zi/work-wechat-robot:~2.0
```

### 1.使用
#### 1.1 直接使用

```php
$robot = new WorkWechatRobot($robotKey);
$robot->text($content); // 文本消息
$robot->markdown($content); // markdown消息
$robot->image($filename); // 图片消息 支持本地图片和网络图片
$robot->news($title, $url, $desc, $picurl); // 图文消息
$robot->file($filename); // 发送上传文件
```

#### 1.2 v2.1.0之后支持创建消息对象发送消息：
```php
$messsage = new Text();     // new Markdwon(); new Image()...
$messsage->content('文本消息');
$messsage->send($robotKey); // 或使用 $robot->message($messsage);
```

### 2.作为Monolog的通道使用
#### 2.1 配置通道
* laravel框架
在配置文件`logging.php`的`channels`数组中增加：

```php
'wxwork_robot' => [  
    'driver' => 'monolog',  
    'level' => 'notice',  
    'handler' => \Huozi\WorkWechat\Monolog\Handler\RobotHandler::class,  
    'handler_with' => [  
        'robotKey' => 'your_wxwork_robot_key',  
    ],  
],
```
 
然后修改`channels`节点`stack`，在`channels`中增加`wxwork_robot`

```php
'stack' => [
    'driver' => 'stack',
    'channels' => ['single', ... , 'wxwork_robot'],
    'ignore_exceptions' => false,
],
```

详见[laravel高度自定义Monolog通道](https://learnku.com/docs/laravel/8.x/logging/9376#advanced-monolog-channel-customization)
* 其他框架

```php
$logger = new \Monolog\Logger($name);
$logger->pushHandler(new RobotHandler($robotKey));
```

#### 2.2 日志格式化
提供了`TextFormatter`和`MarkdownFormatter`格式化原始日志，使日志内容方便阅读
* laravel框架,修改`logging.php`, 增加`formatter`：

```php
'wxwork_robot' => [
    'driver' => 'monolog',
    'level' => 'notice',
    'handler' => \Huozi\WorkWechat\Monolog\Handler\RobotHandler::class,
    'handler_with' => [
        'robotKey' => 'your_wxwork_robot_key',
    ],
    'formatter' => \Huozi\WorkWechat\Monolog\Formatter\MarkdownFormatter::class,```
 ],
 ```

`TextFormatter`和`MarkdownFormatter`都提供了默认的格式化结构，如果需要自定义可以：

```php
    'formatter' => \Huozi\WorkWechat\Monolog\Formatter\TextFormatter::class,
    'formatter_with' => [
        'messageFormat' => '{level_name}:{message} \n {extra.file}:{extra.line}'</b>
    ]
```

* 其他框架

```php
$messageFormat = '{level_name}:{message} \n {extra.file}:{extra.line}';
$formatter = new TextFormatter($messageFormat);
$logger->pushHandler((new RobotHandler($robotKey))->setFormatter($formatter));
```

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
