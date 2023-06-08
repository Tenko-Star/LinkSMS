# LinkSMS

LinkSMS 是凌凯短信平台的第三方SDK

## 快速开始

- 首先：创建一个Config对象

```php
class Config implements \LinkSms\Library\ConfigInterface {
    // 获取基础的URL，具体内容查看对接文档
    public function getBaseUrl(): string
    {
        return 'https://sdk3.028lk.com:9988';
    }

    // 获取账号
    public function getCorpId(): string
    {
        return '[CORP_ID]';
    }

    // 获取密码
    public function getPassword(): string
    {
        return '[PASSWORD]';
    }

    // 如果需要携带请求头，可以在这里返回
    public function getHeaders(): array
    {
        return [];
    }

    // 使用的请求组件为GuzzleHttp，如果使用的时候需要设置Guzzle，可以在这里传递一些配置
    public function getGuzzleConfig(): array
    {
        return [];
    }
}

$config = new Config();
```

如果不想新写一个配置类，可以以匿名类的方式去实现。

```php
$config = new class implements \LinkSms\Library\ConfigInterface {
    public function getBaseUrl(): string
    {
        return 'https://sdk3.028lk.com:9988';
    }

    public function getCorpId(): string
    {
        return '[CORP_ID]';
    }

    public function getPassword(): string
    {
        return '[PASSWORD]';
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getGuzzleConfig(): array
    {
        return [];
    }
};
```

- 如果需要记录日志，可以传入一个日志处理类

```php
$log = new class implements \LinkSms\Library\LogInterface {
    public function info(string $message, array $data = [])
    {
        // TODO write info
    }

    public function warning(string $message, array $data = [])
    {
        // TODO write warning
    }

    public function error(string $message, array $data = [])
    {
        // TODO write error
    }
};
```

- 现在就可以使用了

```php
$sms = new \LinkSms\SmsService($config[, $log]);
```

## 使用方法

- 发送短信

```php
$sms->sendMessage(
    ['13800008888'],
    '内容 【签名】',
    '', // 扩展号，必须为数字或空
    new DateTimeImmutable('2024-01-01 08:00') // 如果需要定时发送就传一个DateTimeImmutable对象
)
```

- 获取短信条数

```php
$remain = $sms->getRemain();
```

- 获取信息

**注意：每次最多只能取到50条，且每条短信仅能取到一次，如果请求失败就无法再次获取到短信了，请求后请及时保存获取到的短信内容**

```php
/** @var array<\LinkSms\Library\Message> $messages */
$messages = $sms->fetchMessage();

foreach ($messages as $message) {
    $mobile = $message->getMobile();
    
    $content = $message->getContent();
    
    /** @var DateTimeImmutable $sendTime */
    $sendTime = $message->getSendTime();
    $sendTimestamp = $sendTime->getTimestamp();
    
    $cell = $message->getCell();
}
```