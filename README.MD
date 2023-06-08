# LinkSMS

LinkSMS is a third-part SDK for Link Group SMS Service.

[中文文档](README-CN.MD)

## Quick Start

- First:  Create a config object

```php
class Config implements \LinkSms\Library\ConfigInterface {
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
}

$config = new Config();
```

Or you can use an anonymous class like this:

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

- If you need to log events, there are also provided interfaces for logging. Here is the usage example:

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

- And then you can start using it.

```php
$sms = new \LinkSms\SmsService($config[, $log]); // It is not necessary to pass a LogInterface
```

## Usage

- Send message

```php
$sms->sendMessage(
    ['13800008888'],
    'content 【Sign】',
    '', // Cell, must be a numeric string,
    new DateTimeImmutable('2024-01-01 08:00') // It is not necessary unless you need to schedule the sending of text messages.
)
```

- Get remain

```php
$remain = $sms->getRemain();
```

- Fetch message

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