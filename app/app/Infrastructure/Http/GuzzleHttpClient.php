<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\HttpClientInterface;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleHttpClient implements HttpClientInterface
{
    private readonly ClientInterface $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0,
            'connect_timeout' => 5.0
        ]);
    }

    public function get(string $url, array $params = []): array
    {
        try {
            Log::channel('swapi')->debug("Starting HTTP request", [
                'method' => 'GET',
                'url' => $url,
                'params' => $params
            ]);
            
            $startTime = microtime(true);
            
            $response = $this->client->request('GET', $url, [
                'query' => $params
            ]);
            
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;
            
            $responseData = json_decode($response->getBody()->getContents(), true);
            
            Log::channel('swapi')->debug("HTTP request completed successfully", [
                'method' => 'GET',
                'url' => $url,
                'status_code' => $response->getStatusCode(),
                'execution_time_ms' => round($executionTime, 2),
                'response_size' => strlen(json_encode($responseData)),
                'headers' => $response->getHeaders()
            ]);

            return $responseData;
        } catch (GuzzleException $e) {
            Log::channel('swapi')->error("HTTP request failed", [
                'method' => 'GET',
                'url' => $url,
                'params' => $params,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'exception' => get_class($e),
                'stack' => $e->getTraceAsString()
            ]);
            
            throw new \Exception("Failed to fetch data: {$e->getMessage()}");
        }
    }
} 