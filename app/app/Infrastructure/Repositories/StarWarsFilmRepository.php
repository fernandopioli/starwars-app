<?php

namespace App\Infrastructure\Repositories;

use App\Application\Interfaces\Repositories\FilmRepositoryInterface;
use App\Domain\Entities\Film;
use App\Infrastructure\Http\HttpClientInterface;

class StarWarsFilmRepository implements FilmRepositoryInterface
{
    private const BASE_URL = 'https://swapi.dev/api';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function findAll(?string $query): array
    {
        $response = $this->httpClient->get(self::BASE_URL . '/films', ['search' => $query]);
        return array_map(
            fn($film) => $this->mapResponseToFilm($film),
            $response['results']
        );
    }

    public function findById(string $id): ?Film
    {
        try {
            $response = $this->httpClient->get(self::BASE_URL . "/films/$id");
            return $this->mapResponseToFilm($response);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function mapResponseToFilm(array $data): Film
    {
        return Film::fromArray([
            'id' => $this->extractIdFromUrl($data['url']),
            'title' => $data['title'],
            'episode_id' => $data['episode_id'],
            'opening_crawl' => $data['opening_crawl'],
            'director' => $data['director'],
            'producer' => $data['producer'],
            'release_date' => $data['release_date'],
            'characters' => $data['characters']
        ]);
    }

    private function extractIdFromUrl(string $url): string
    {
        preg_match('/\/(\d+)\/?$/', $url, $matches);
        return $matches[1];
    }
} 