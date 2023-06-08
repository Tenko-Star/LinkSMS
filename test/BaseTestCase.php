<?php

namespace LinkSms\Test;

use LinkSms\Test\lib\Config;
use LinkSms\Test\lib\ConsoleLog;
use PHPUnit\Framework\TestCase;
use LinkSms\Library\ConfigInterface;
use LinkSms\Library\LogInterface;

class BaseTestCase extends TestCase
{
    protected ConfigInterface $config;

    protected array $env;

    protected LogInterface $log;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        try {
            $env = fopen('.env', 'r');
            $this->env = [];

            while ($line = fgets($env)) {
                if (strpos($line, '#') === 0) {
                    continue;
                }

                @[$key, $value] = explode('=', $line);

                if ($value !== null) {
                    $value = trim($value);
                }

                $key = strtolower(trim($key));

                if (isset($this->env[$key])) {
                    if (is_array($this->env[$key])) {
                        $this->env[$key][] = $value;
                    } else {
                        $this->env[$key] = [
                            $value
                        ];
                    }

                    continue;
                }

                @[$mainKey, $subKey] = explode('.', $key);
                if (!empty($subKey)) {
                    if (!isset($this->env[$mainKey])) {
                        $this->env[$mainKey] = [];
                    }
                    $this->env[$mainKey][$subKey] = $value;
                } else {
                    $this->env[$key] = $value;
                }
            }

            $this->config = new Config(
                $this->env['base_url'] ?? '',
                $this->env['corp_id'] ?? '',
                $this->env['password'] ?? ''
            );

            if (isset($this->env['header'])) {
                $this->config->setHeaders($this->env['header']);
            }

            if (isset($this->env['guzzle'])) {
                $this->config->setGuzzleConfig($this->env['guzzle']);
            }

        } finally {
            fclose($env);
        }

        $this->log = new ConsoleLog();
    }
}