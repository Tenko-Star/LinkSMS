<?php

namespace LinkSms\Test\lib;

use LinkSms\Library\ConfigInterface;

class Config implements ConfigInterface
{
    private string $baseUrl;

    private string $corpId;

    private string $password;

    private array $headers = [];

    private array $guzzleConfigs = [];

    /**
     * @param string $baseUrl
     * @param string $corpId
     * @param string $password
     */
    public function __construct(string $baseUrl, string $corpId, string $password)
    {
        $this->baseUrl = $baseUrl;
        $this->corpId = $corpId;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getCorpId(): string
    {
        return $this->corpId;
    }

    /**
     * @param string $corpId
     */
    public function setCorpId(string $corpId): void
    {
        $this->corpId = $corpId;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getGuzzleConfig(): array
    {
        return $this->guzzleConfigs;
    }

    /**
     * @param array $guzzleConfigs
     */
    public function setGuzzleConfig(array $guzzleConfigs): void
    {
        $this->guzzleConfigs = $guzzleConfigs;
    }
}