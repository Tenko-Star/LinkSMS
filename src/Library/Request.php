<?php

namespace LinkSms\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientExceptionInterface;
use LinkSms\Exception\RequestException;

final class Request
{
    private Client $client;

    private LogInterface $log;

    private array $headers;

    private string $baseUrl;

    private static string $errMsg = '';

    public function __construct(ConfigInterface $config, LogInterface $log)
    {
        $this->client = new Client($config->getGuzzleConfig());

        $this->headers = $config->getHeaders();

        $this->baseUrl = $config->getBaseUrl();

        $this->log = $log;
    }

    public function request(string $api, array $params = [], string $method = 'GET'): ?Response
    {
        $url = rtrim($this->baseUrl, '/') . '/' . trim($api, '/?');
        $method = strtoupper($method);

        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $url .= $this->parseQuery($params, true);
                }

                $request = new \GuzzleHttp\Psr7\Request($method, $url, $this->headers);
                break;

            case 'POST':
                $stream = Utils::streamFor($this->parseQuery($params));
                $request = new \GuzzleHttp\Psr7\Request($method, $url, $this->headers, $stream);
                break;
            default:
                throw new RequestException('Unsupported method');
        }

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            self::setError($e->getMessage());
            $this->log->warning($e->getMessage(), $e->getTrace());
            return null;
        }

        return $response;
    }

    public function get(string $api, array $params = []): ?Response
    {
        return $this->request($api, $params);
    }

    public function post(string $api, array $params = []): ?Response
    {
        return $this->request($api, $params, 'POST');
    }

    private function parseQuery(array $params, bool $withQ = false): string
    {
        $data = [];

        foreach ($params as $key => $param) {
            if (is_int($key)) {
                $data[] = "$param=";
            } else {
                $data[] = "$key=$param";
            }
        }

        return ($withQ ? '?' : '') . implode('&', $data);
    }

    private static function setError(string $msg): void
    {
        self::$errMsg = $msg;
    }

    public static function getError(): string
    {
        return self::$errMsg;
    }
}