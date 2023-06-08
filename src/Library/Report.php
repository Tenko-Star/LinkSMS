<?php

namespace LinkSms\Library;

use DateTimeImmutable;

class Report implements \JsonSerializable
{
    private string $id;

    private string $mobile;

    private DateTimeImmutable $sendTime;

    private int $reportFlag;

    private string $reportMessage;

    private DateTimeImmutable $reportTime;

    /**
     * @param string $id
     * @param string $mobile
     * @param DateTimeImmutable $sendTime
     * @param int $reportFlag
     * @param string $reportMessage
     * @param DateTimeImmutable $reportTime
     */
    public function __construct(
        string            $id,
        string            $mobile,
        DateTimeImmutable $sendTime,
        int               $reportFlag,
        string            $reportMessage,
        DateTimeImmutable $reportTime
    ) {
        $this->id = $id;
        $this->mobile = $mobile;
        $this->sendTime = $sendTime;
        $this->reportFlag = $reportFlag;
        $this->reportMessage = $reportMessage;
        $this->reportTime = $reportTime;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getSendTime(): DateTimeImmutable
    {
        return $this->sendTime;
    }

    /**
     * @return int
     */
    public function getReportFlag(): int
    {
        return $this->reportFlag;
    }

    /**
     * @return string
     */
    public function getReportMessage(): string
    {
        return $this->reportMessage;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getReportTime(): DateTimeImmutable
    {
        return $this->reportTime;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'mobile' => $this->mobile,
            'sendTime' => $this->sendTime->format('Y-m-d H:i:s'),
            'reportFlag' => $this->reportFlag,
            'reportMessage' => $this->reportMessage,
            'reportTime' => $this->reportTime->format('Y-m-d H:i:s'),
        ];
    }
}