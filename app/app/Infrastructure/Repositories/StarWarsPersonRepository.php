<?php

namespace App\Infrastructure\Repositories;

use App\Application\Interfaces\Repositories\PersonRepositoryInterface;
use App\Domain\Entities\Person;
use App\Infrastructure\Http\HttpClientInterface;

class StarWarsPersonRepository implements PersonRepositoryInterface
{
    private const BASE_URL = 'https://swapi.dev/api';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function findAll(?string $query = null): array
    {
        
        $response = $this->httpClient->get(self::BASE_URL . '/people', ['search' => $query]);
        
        return array_map(
            fn($personData) => $this->mapResponseToPerson($personData),
            $response['results']
        );

    }

    public function findById(string $id): ?Person
    {
        try {
            $response = $this->httpClient->get(self::BASE_URL . "/people/{$id}");
            return $this->mapResponseToPerson($response);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function mapResponseToPerson(array $data): Person
    {
        return Person::fromArray([
            'id' => $this->extractIdFromUrl($data['url']),
            'name' => $data['name'],
            'height' => $data['height'],
            'mass' => $data['mass'],
            'hair_color' => $data['hair_color'],
            'skin_color' => $data['skin_color'],
            'eye_color' => $data['eye_color'],
            'birth_year' => $data['birth_year'],
            'gender' => $data['gender'],
            'films' => $data['films']
        ]);
    }

    private function extractIdFromUrl(string $url): string
    {
        preg_match('/\/(\d+)\/?$/', $url, $matches);
        return $matches[1];
    }
} 