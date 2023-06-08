<?php

namespace LinkSms\Test;

use LinkSms\SmsService;

require_once '../vendor/autoload.php';

class TestRemain extends BaseTestCase
{
    public function testRemain()
    {
        $sms = new SmsService($this->config, $this->log);

        $remain = $sms->getRemain();

        $this->assertGreaterThan(0, $remain);

        fwrite(STDERR, 'remain: ' . $remain);
    }


}
