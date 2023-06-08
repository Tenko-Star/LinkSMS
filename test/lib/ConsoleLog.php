<?php

namespace LinkSms\Test\lib;

use LinkSms\Library\LogInterface;

class ConsoleLog implements LogInterface
{

    public function info(string $message, array $data = [])
    {
        fwrite(STDERR, "\n[INFO] " . $message . "\n[DATA]\n" . json_encode($data));
    }

    public function warning(string $message, array $data = [])
    {
        fwrite(STDERR, "\n[WARN] " . $message . "\n[DATA]\n" . json_encode($data));
    }

    public function error(string $message, array $data = [])
    {
        fwrite(STDERR, "\n[ERRO] " . $message . "\n[DATA]\n" . json_encode($data));
    }
}