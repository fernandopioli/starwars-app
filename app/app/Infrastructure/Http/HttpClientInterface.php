<?php

namespace App\Infrastructure\Http;

interface HttpClientInterface
{
    public function get(string $url, array $params = []): array;
} 