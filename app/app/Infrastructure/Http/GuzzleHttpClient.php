<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleHttpClient implements HttpClientInterface
{
    private readonly ClientInterface $client;

    public function __construct(
    ) {
        $this->client = new Client();
    }

    public function get(string $url, array $params = []): array
    {
        try {
            $response = $this->client->request('GET', $url, [
                'query' => $params
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \Exception("Failed to fetch data: {$e->getMessage()}");
        }
    }
} 