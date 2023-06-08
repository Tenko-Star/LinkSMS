<?php

namespace LinkSms\Library;

class DefaultLog implements LogInterface
{

    public function info(string $message, array $data = [])
    {
        return;
    }

    public function warning(string $message, array $data = [])
    {
        return;
    }

    public function error(string $message, array $data = [])
    {
        return;
    }
}