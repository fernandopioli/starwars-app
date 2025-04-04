<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Film;
use App\Domain\ValueObjects\EntityReference;
use App\Application\Interfaces\Repositories\FilmRepositoryInterface;
use App\Application\Interfaces\Repositories\PersonRepositoryInterface;
use App\Infrastructure\Http\HttpClientInterface;
use App\Domain\Events\QueryPerformed;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StarWarsFilmRepository implements FilmRepositoryInterface
{
    private const BASE_URL = 'https://swapi.dev/api';
    private const CACHE_TTL = 3600;

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function findAll(?string $query): array
    {

        event(new QueryPerformed('film-' . ($query ?? 'all'), 'film'));


        $cacheKey = 'film_' . ($query ?? 'all');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query) {
            Log::channel('swapi')->info("Starting call to list films", ['query' => $query]);
            $startTime = microtime(true);

            $response = $this->httpClient->get(self::BASE_URL . '/films', ['search' => $query]);

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;
            Log::channel('swapi')->info("Call to list films completed", [
                'query' => $query,
                'execution_time_ms' => round($executionTime, 2),
                'results_count' => count($response['results'] ?? [])
            ]);

            return array_map(
                fn(array $film) => $this->mapResponseToFilm($film),
                $response['results']
            );
        });
    }


    public function findById(string $id, bool $withEnrichment = true): ?Film
    {
        if($withEnrichment === true) event(new QueryPerformed("film-" . $id, 'film'));

        $cacheKey = 'film_' . $id;

        if (Cache::has($cacheKey)) {
            Log::channel('swapi')->info("Cache HIT for film", [
                'film_id' => $id,
                'cache_key' => $cacheKey
            ]);
            $film = Cache::get($cacheKey);

            if (!$withEnrichment && $film) {
                Log::channel('swapi')->info("Returning non-enriched film from cache", ['film_id' => $id]);
                return $film;
            }

            if ($film && $withEnrichment) {
                $charactersNeedEnrichment = false;
                foreach ($film->getCharacters() as $character) {
                    if ($character->name === null) {
                        $charactersNeedEnrichment = true;
                        Log::channel('swapi')->warning("Found character with null name in cache", [
                            'film_id' => $id,
                            'person_id' => $character->id
                        ]);
                        break;
                    }
                }

                if (!$charactersNeedEnrichment) {
                    Log::channel('swapi')->info("All characters already have names, returning cached film", ['film_id' => $id]);
                    return $film;
                }

                Log::channel('swapi')->info("Re-enriching film's characters", ['film_id' => $id]);
                Cache::forget($cacheKey);
            }
        }

        Log::channel('swapi')->info("Cache MISS for film", [
            'film_id' => $id,
            'cache_key' => $cacheKey
        ]);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id, $withEnrichment) {
            try {
                Log::channel('swapi')->info("Starting call to find film by ID", ['film_id' => $id]);
                $startTime = microtime(true);

                $response = $this->httpClient->get(self::BASE_URL . "/films/$id");

                $endTime = microtime(true);
                $executionTime = ($endTime - $startTime) * 1000;
                Log::channel('swapi')->info("Call to find film completed", [
                    'film_id' => $id,
                    'execution_time_ms' => round($executionTime, 2)
                ]);

                $film = $this->mapResponseToFilm($response);

                if ($withEnrichment) {
                    Log::channel('swapi')->info("Starting film characters enrichment", ['film_id' => $id]);
                    $startTimeEnrich = microtime(true);

                    $enrichedFilm = $this->enrichFilmCharactersWithNames($film);

                    $endTimeEnrich = microtime(true);
                    $executionTimeEnrich = ($endTimeEnrich - $startTimeEnrich) * 1000;
                    Log::channel('swapi')->info("Characters enrichment completed", [
                        'film_id' => $id,
                        'execution_time_ms' => round($executionTimeEnrich, 2),
                        'characters_count' => count($film->getCharacters())
                    ]);

                    return $enrichedFilm;
                }

                return $film;
            } catch (\Exception $e) {
                Log::channel('swapi')->error("Error finding film", [
                    'film_id' => $id,
                    'error' => $e->getMessage(),
                    'stack' => $e->getTraceAsString()
                ]);
                return null;
            }
        });
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

    private function enrichFilmCharactersWithNames(Film $film): Film
    {
        Log::channel('swapi')->info("Starting to enrich characters for film", [
            'film_id' => $film->getId(),
            'character_count' => count($film->getCharacters())
        ]);

        $charactersWithNames = array_map(function (EntityReference $character) {
            $characterId = $character->id;

            $personCacheKey = 'person_name' . $characterId;
            if (Cache::has($personCacheKey)) {
                $cachedPerson = Cache::get($personCacheKey);
                if ($cachedPerson) {
                    Log::channel('swapi')->info("Using cached person for name", [
                        'person_id' => $characterId,
                        'name' => $cachedPerson
                    ]);
                    return new EntityReference(
                        id: $characterId,
                        name: $cachedPerson
                    );
                }
            }


            $name = Cache::remember($personCacheKey, self::CACHE_TTL, function () use ($characterId) {
                try {
                    Log::channel('swapi')->debug("Fetching person name directly", ['person_id' => $characterId]);
                    $startTime = microtime(true);

                    $person = app(PersonRepositoryInterface::class)->findById($characterId, false);

                    $endTime = microtime(true);
                    $executionTime = ($endTime - $startTime) * 1000;

                    $nameResult = $person ? $person->getName() : 'Unknown';

                    Log::channel('swapi')->debug("Person name obtained", [
                        'person_id' => $characterId,
                        'name' => $nameResult,
                        'execution_time_ms' => round($executionTime, 2)
                    ]);

                    return $nameResult;
                } catch (\Exception $e) {
                    Log::channel('swapi')->warning("Error fetching person name", [
                        'person_id' => $characterId,
                        'error' => $e->getMessage()
                    ]);
                    return 'Unknown Character';
                }
            });

            Log::channel('swapi')->info("Character enriched with name", [
                'person_id' => $characterId,
                'name' => $name
            ]);

            return new EntityReference(
                id: $characterId,
                name: $name
            );
        }, $film->getCharacters());


        $film = $film->withCharacters($charactersWithNames);

        Log::channel('swapi')->info("Characters enrichment completed", [
            'film_id' => $film->getId(),
            'enriched_characters' => array_map(function($character) {
                return [
                    'id' => $character->id,
                    'name' => $character->name
                ];
            }, $charactersWithNames)
        ]);

        return $film;
    }

    private function extractIdFromUrl(string $url): string
    {
        preg_match('/\/(\d+)\/?$/', $url, $matches);
        return $matches[1];
    }
}