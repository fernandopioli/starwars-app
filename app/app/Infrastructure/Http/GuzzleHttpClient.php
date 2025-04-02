<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class GuzzleHttpClient implements HttpClientInterface
{
    private readonly ClientInterface $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0, // 10 seconds timeout
            'connect_timeout' => 5.0 // 5 seconds for connection
        ]);
    }

    public function get(string $url, array $params = []): array
    {
        try {
            // Log at the beginning of the request
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
            $executionTime = ($endTime - $startTime) * 1000; // in milliseconds
            
            $responseData = json_decode($response->getBody()->getContents(), true);
            
            // Log at the end of successful request
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
            // Log request error
            Log::channel('swapi')->error("HTTP request failed", [
                'method' => 'GET',
                'url' => $url,
                'params' => $params,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'stack' => $e->getTraceAsString()
            ]);
            
            throw new \Exception("Failed to fetch data: {$e->getMessage()}");
        }
    }
} 