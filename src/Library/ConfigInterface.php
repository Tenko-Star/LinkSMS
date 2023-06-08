<?php

namespace LinkSms\Library;

interface ConfigInterface
{
    public function getBaseUrl(): string;

    public function getCorpId(): string;

    public function getPassword(): string;

    public function getHeaders(): array;

    public function getGuzzleConfig(): array;
}