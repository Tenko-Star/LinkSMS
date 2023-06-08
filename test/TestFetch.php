<?php

namespace LinkSms\Test;

use LinkSms\SmsService;

require_once '../vendor/autoload.php';

class TestFetch extends BaseTestCase
{
    public function testFetchMessage()
    {
        $sms = new SmsService($this->config, $this->log);

        $messages = $sms->fetchMessage();

        fwrite(STDERR, "Result: \n");
        fwrite(STDERR, json_encode($messages));

        $this->assertIsArray($messages);
    }
}
