<?php

namespace LinkSms;

use DateTimeImmutable;
use GuzzleHttp\Psr7\Response;
use LinkSms\Exception\SmsException;
use LinkSms\Library\ConfigInterface;
use LinkSms\Library\DefaultLog;
use LinkSms\Library\ErrorMap;
use LinkSms\Library\LogInterface;
use LinkSms\Library\Message;
use LinkSms\Library\Request;

class SmsService
{
    private ConfigInterface $config;

    private Request $request;

    private LogInterface $log;

    public function __construct(ConfigInterface $config, ?LogInterface $log = null)
    {
        $this->config = $config;

        if ($log) {
            $this->log = $log;
        } else {
            $this->log = new DefaultLog();
        }

        $this->request = new Request($config, $this->log);
    }

    /**
     * Send sms message to server.
     *
     * @param array $mobile
     * @param string $content
     * @param string $cell 扩展号（必须为数字或空）
     * @param DateTimeImmutable|null $sendTime 定时发送时间（可为空）
     * @return int
     * @throws SmsException
     */
    public function sendMessage(array $mobile, string $content, string $cell = '', ?DateTimeImmutable $sendTime = null): int
    {
        $apiName = 'BatchSend2';

        $convertedText = rawurlencode(mb_convert_encoding($content, "gb2312", "utf-8"));

        if (!empty($cell) && !is_numeric($cell)) {
            throw new SmsException('Cell must be a numeric string.');
        }

        $mobileString = implode(',', $mobile);
        if (empty($mobileString)) {
            throw new SmsException('At least one phone number needs to be provided.');
        }

        $data = [
            'CorpID' => $this->config->getCorpId(),
            'Pwd' => $this->config->getPassword(),
            'Mobile' => $mobileString,
            'Content' => $convertedText,
            'Cell' => $cell,
        ];

        if ($sendTime) {
            $sendTimeStr = $sendTime->format('YmdHis');
            $data['SendTime'] = $sendTimeStr;
        }

        $response = $this->request->get($apiName . '.aspx', $data);

        $responseData = $this->checkResponse($apiName, $response);

        if (!is_numeric($responseData)) {
            throw new SmsException('Api data changed. Please check document. Message: ' . json_encode($responseData));
        }

        return (int)$responseData;
    }

    /**
     * Fetch sms messages from server.
     *
     * @return array<Message>
     * @throws SmsException
     */
    public function fetchMessage(): array
    {
        $apiName = 'Get';

        $data = [
            'CorpID' => $this->config->getCorpId(),
            'Pwd' => $this->config->getPassword(),
        ];

        $response = $this->request->get($apiName . '.aspx', $data);

        $responseData = $this->checkResponse($apiName, $response);

        return $this->parseMessageStr($responseData);
    }

    /**
     * @param string $data
     * @return array<Message>
     */
    public function parseMessageStr(string $data): array
    {
        $messages = explode('||', $data);

        /** @var array<Message> $result */
        $result = [];

        foreach ($messages as $message) {
            $message = trim($message);

            if (empty($message)) {
                continue;
            }

            $data = explode('#', $message);
            if (count($data) !== 4) {
                $this->log->warning('Unexpected message: ' . $message);
                continue;
            }

            try {
                $messageStruct = new Message(
                    $data[0],
                    $data[1],
                    new DateTimeImmutable($data[2], new \DateTimeZone('Asia/Shanghai')),
                    $data[3],
                );

                $result[] = $messageStruct;
            } catch (\Exception $e) {
                $this->log->warning('Invalid date time format: ' . $data[2]);
                continue;
            }
        }

        return $result;
    }

    /**
     * Get remaining of sms.
     *
     * @return int
     * @throws SmsException
     */
    public function getRemain(): int
    {
        $apiName = 'SelSum';

        $data = [
            'CorpID' => $this->config->getCorpId(),
            'Pwd' => $this->config->getPassword(),
        ];

        $response = $this->request->get($apiName . '.aspx', $data);

        $responseData = $this->checkResponse($apiName, $response);

        if (!is_numeric($responseData)) {
            throw new SmsException('Api data changed. Please check document.');
        }

        return (int)$responseData;
    }

    private function checkResponse(string $apiName, Response $response): string
    {
        $content = $response->getBody()->getContents();

        if (is_numeric($content) && (int)$content < 0) {
            throw new SmsException(ErrorMap::getMessage($apiName, (int)$content));
        }

        return $content;
    }
}