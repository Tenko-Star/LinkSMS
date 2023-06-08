<?php

namespace LinkSms\Library;

interface LogInterface
{
    public function info(string $message, array $data = []);

    public function warning(string $message, array $data = []);

    public function error(string $message, array $data = []);
}