<?php

namespace App\Infrastructure\Repositories;

use App\Application\Interfaces\Repositories\FilmRepositoryInterface;
use App\Application\Interfaces\Repositories\PersonRepositoryInterface;
use App\Domain\Entities\Person;
use App\Domain\ValueObjects\EntityReference;
use App\Events\QueryPerformed;
use App\Infrastructure\Http\HttpClientInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StarWarsPersonRepository implements PersonRepositoryInterface
{
    private const BASE_URL = 'https://swapi.dev/api';
    private const CACHE_TTL = 3600;

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function findAll(?string $query): array
    {
        event(new QueryPerformed('people-' . ($query ?? 'all'), 'person'));

        
        $cacheKey = 'people_' . ($query ?? 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query) {
            Log::channel('swapi')->info("Starting call to list people", ['query' => $query]);
            $startTime = microtime(true);
            
            $response = $this->httpClient->get(self::BASE_URL . '/people', ['search' => $query]);
            
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;
            Log::channel('swapi')->info("Call to list people completed", [
                'query' => $query,
                'execution_time_ms' => round($executionTime, 2),
                'results_count' => count($response['results'] ?? [])
            ]);
            
            return array_map(
                fn($person) => $this->mapResponseToPerson($person),
                $response['results']
            );
        });
    }

    public function findById(string $id, bool $withEnrichment = true): ?Person
    {
        // Registrar a consulta por ID
        event(new \App\Events\QueryPerformed("people-" . $id, 'person'));
        
        $cacheKey = 'person_' . $id;
        
        if (Cache::has($cacheKey)) {
            Log::channel('swapi')->info("Cache HIT for person", [
                'person_id' => $id,
                'cache_key' => $cacheKey
            ]);
            $person = Cache::get($cacheKey);
            
            if (!$withEnrichment && $person) {
                Log::channel('swapi')->info("Returning non-enriched person from cache", ['person_id' => $id]);
                return $person;
            }
            
            if ($person && $withEnrichment) {
                $filmsNeedEnrichment = false;
                
                foreach ($person->getFilms() as $film) {
                    if ($film->name === null) {
                        $filmsNeedEnrichment = true;
                        Log::channel('swapi')->warning("Found film with null name in cache", [
                            'person_id' => $id,
                            'film_id' => $film->id
                        ]);
                        break;
                    }
                }
                
                if (!$filmsNeedEnrichment) {
                    Log::channel('swapi')->info("All films already have names, returning cached person", ['person_id' => $id]);
                    return $person;
                }
                
                Log::channel('swapi')->info("Re-enriching person's films", ['person_id' => $id]);
                Cache::forget($cacheKey);
            }
        }
        
        Log::channel('swapi')->info("Cache MISS for person", [
            'person_id' => $id,
            'cache_key' => $cacheKey
        ]);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id, $withEnrichment) {
            try {
                Log::channel('swapi')->info("Starting call to find person by ID", ['person_id' => $id]);
                $startTime = microtime(true);
                
                $response = $this->httpClient->get(self::BASE_URL . "/people/$id");
                
                $endTime = microtime(true);
                $executionTime = ($endTime - $startTime) * 1000; // in milliseconds
                Log::channel('swapi')->info("Call to find person completed", [
                    'person_id' => $id, 
                    'execution_time_ms' => round($executionTime, 2)
                ]);
                
                $person = $this->mapResponseToPerson($response);
                
                if ($withEnrichment) {
                    Log::channel('swapi')->info("Starting person films enrichment", ['person_id' => $id]);
                    $startTimeEnrich = microtime(true);
                    
                    $enrichedPerson = $this->enrichPersonFilmsWithTitles($person);
                    
                    $endTimeEnrich = microtime(true);
                    $executionTimeEnrich = ($endTimeEnrich - $startTimeEnrich) * 1000; // in milliseconds
                    Log::channel('swapi')->info("Films enrichment completed", [
                        'person_id' => $id,
                        'execution_time_ms' => round($executionTimeEnrich, 2),
                        'films_count' => count($person->getFilms())
                    ]);
                    
                    return $enrichedPerson;
                }
                
                return $person;
            } catch (\Exception $e) {
                Log::channel('swapi')->error("Error finding person", [
                    'person_id' => $id,
                    'error' => $e->getMessage(),
                    'stack' => $e->getTraceAsString()
                ]);
                return null;
            }
        });
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

    private function enrichPersonFilmsWithTitles(Person $person): Person
    {
        Log::channel('swapi')->info("Starting to enrich films for person", [
            'person_id' => $person->getId(),
            'film_count' => count($person->getFilms())
        ]);

        $filmsWithTitles = array_map(function (EntityReference $film) {
            $filmId = $film->id;
            
            // Verificar se já temos o filme completo em cache
            $filmCacheKey = 'film_' . $filmId;
            if (Cache::has($filmCacheKey)) {
                $cachedFilm = Cache::get($filmCacheKey);
                if ($cachedFilm) {
                    Log::channel('swapi')->info("Using cached film for title", [
                        'film_id' => $filmId,
                        'title' => $cachedFilm->getTitle()
                    ]);
                    return new EntityReference(
                        id: $filmId,
                        name: $cachedFilm->getTitle()
                    );
                }
            }
            
            $titleCacheKey = 'film_title_' . $filmId;
            
            $title = Cache::remember($titleCacheKey, self::CACHE_TTL, function () use ($filmId) {
                try {
                    Log::channel('swapi')->debug("Fetching film title directly", ['film_id' => $filmId]);
                    $startTime = microtime(true);
                    
                    $film = app(FilmRepositoryInterface::class)->findById($filmId, false);
                    
                    $endTime = microtime(true);
                    $executionTime = ($endTime - $startTime) * 1000;
                    
                    $titleResult = $film ? $film->getTitle() : 'Unknown';
                    
                    Log::channel('swapi')->debug("Film title obtained", [
                        'film_id' => $filmId,
                        'title' => $titleResult,
                        'execution_time_ms' => round($executionTime, 2)
                    ]);
                    
                    return $titleResult;
                } catch (\Exception $e) {
                    Log::channel('swapi')->warning("Error fetching film title", [
                        'film_id' => $filmId,
                        'error' => $e->getMessage()
                    ]);
                    return 'Unknown Film';
                }
            });
            
            Log::channel('swapi')->info("Film enriched with title", [
                'film_id' => $filmId,
                'title' => $title
            ]);
            
            return new EntityReference(
                id: $filmId,
                name: $title
            );
        }, $person->getFilms());
        
        $result = Person::fromArray([
            'id' => $person->getId(),
            'name' => $person->getName(),
            'height' => $person->getHeight(),
            'mass' => $person->getMass(),
            'hair_color' => $person->getHairColor(),
            'skin_color' => $person->getSkinColor(),
            'eye_color' => $person->getEyeColor(),
            'birth_year' => $person->getBirthYear(),
            'gender' => $person->getGender(),
            'films' => $filmsWithTitles
        ]);
        
        // Log dos resultados do enriquecimento
        Log::channel('swapi')->info("Films enrichment completed", [
            'person_id' => $person->getId(),
            'enriched_films' => array_map(function($film) {
                return [
                    'id' => $film->id,
                    'name' => $film->name
                ];
            }, $filmsWithTitles)
        ]);
        
        return $result;
    }

    private function extractIdFromUrl(string $url): string
    {
        preg_match('/\/(\d+)\/?$/', $url, $matches);
        return $matches[1];
    }
} 