<?php

namespace LinkSms\Library;

use DateTimeImmutable;

class Message implements \JsonSerializable
{
    private string $mobile;

    private string $content;

    private DateTimeImmutable $sendTime;

    private string $cell;

    /**
     * @param string $mobile
     * @param string $content
     * @param DateTimeImmutable $sendTime
     * @param string $cell
     */
    public function __construct(string $mobile, string $content, DateTimeImmutable $sendTime, string $cell)
    {
        $this->mobile = $mobile;
        $this->content = $content;
        $this->sendTime = $sendTime;
        $this->cell = $cell;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getSendTime(): DateTimeImmutable
    {
        return $this->sendTime;
    }

    /**
     * @return string
     */
    public function getCell(): string
    {
        return $this->cell;
    }

    public function jsonSerialize(): array
    {
        return [
            'mobile' => $this->mobile,
            'content' => $this->content,
            'send_time' => $this->sendTime->format('Y-m-d H:i:s'),
            'cell' => $this->cell
        ];
    }
}