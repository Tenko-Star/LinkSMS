<?php

namespace LinkSms\Test;

require_once '../vendor/autoload.php';

class TestMessage extends BaseTestCase
{
    public function testMessageStruct()
    {
        $sms = new \LinkSms\SmsService($this->config, $this->log);

        $str = '||13899990000#test#20060912152435#||13899990001#test1#20060912152435#010||13899990002#test2#20060912152435#777888';

        $messages = $sms->parseMessageStr($str);

        $this->assertIsArray($messages);

        $this->assertEquals('13899990000', $messages[0]->getMobile());
        $this->assertEquals('test', $messages[0]->getContent());
        $this->assertEquals(1158045875, $messages[0]->getSendTime()->getTimestamp());
        $this->assertEquals('', $messages[0]->getCell());

        $this->assertEquals('13899990002', $messages[2]->getMobile());
        $this->assertEquals('test2', $messages[2]->getContent());
        $this->assertEquals(1158045875, $messages[2]->getSendTime()->getTimestamp());
        $this->assertEquals('777888', $messages[2]->getCell());
    }
}
