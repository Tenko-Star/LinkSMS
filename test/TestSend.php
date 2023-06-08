<?php
declare(strict_types=1);

namespace LinkSms\Test;

require_once '../vendor/autoload.php';

use LinkSms\Exception\SmsException;
use LinkSms\SmsService;

class TestSend extends BaseTestCase
{
    private function getInfo(): array
    {
        $mobile = (array)$this->env['mobile'] ?? [];
        $this->assertIsArray($mobile);
        $this->assertNotEmpty($mobile);

        $content = $this->env['content'] ?? '';
        $this->assertIsString($content);
        $this->assertNotEmpty($content);

        $cell = $this->env['cell'] ?? '';
        $this->assertIsString($cell);
        if (!empty($cell)) {
            $this->assertIsNumeric($cell);
        }

        $schedule = $this->env['schedule'] ?? '';
        $this->assertIsString($schedule);
        $scheduleTime = new \DateTimeImmutable($schedule, new \DateTimeZone('Asia/Shanghai'));

        return [
            $mobile,
            $content,
            $cell,
            $scheduleTime
        ];
    }

    public function testSend()
    {
        $sms = new SmsService($this->config, $this->log);

        [$mobile, $content, $cell] = $this->getInfo();

        $result = $sms->sendMessage(
            $mobile,
            $content,
            $cell
        );

        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testScheduleSend()
    {
        $sms = new SmsService($this->config, $this->log);

        [$mobile, $content, $cell, $scheduleTime] = $this->getInfo();

        $result = $sms->sendMessage(
            $mobile,
            $content,
            $cell,
            $scheduleTime
        );

        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testCellException()
    {
        $this->expectException(SmsException::class);

        $sms = new SmsService($this->config, $this->log);

        $mobile = ['13899990000'];
        $content = 'test';
        $cell = 'abc';

        $sms->sendMessage(
            $mobile,
            $content,
            $cell
        );
    }

    public function testMobileException()
    {
        $this->expectException(SmsException::class);

        $sms = new SmsService($this->config, $this->log);

        $mobile = [];
        $content = 'test';
        $cell = 'abc';

        $sms->sendMessage(
            $mobile,
            $content,
            $cell
        );
    }

    public function testContentException()
    {
        $this->expectException(SmsException::class);

        $sms = new SmsService($this->config, $this->log);

        $mobile = ['13899990000'];
        $content = mb_convert_encoding('测试测试', 'UTF-16');
        $cell = 'abc';

        $sms->sendMessage(
            $mobile,
            $content,
            $cell
        );
    }
}
