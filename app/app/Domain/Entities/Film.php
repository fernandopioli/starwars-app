<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\EntityReference;

use InvalidArgumentException;

class Film
{
    private string $id;
    private string $title;
    private int $episodeId;
    private string $openingCrawl;
    private string $director;
    private string $producer;
    private string $releaseDate;
    private array $characters;

    private function __construct(
        string $id,
        string $title,
        int $episodeId,
        string $openingCrawl,
        string $director,
        string $producer,
        string $releaseDate,
        array $characters,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->episodeId = $episodeId;
        $this->openingCrawl = $openingCrawl;
        $this->director = $director;
        $this->producer = $producer;
        $this->releaseDate = $releaseDate;
        $this->characters = $characters;
    }

    public static function fromArray(array $data): self
    {
        self::validate($data);
        
        $characters = [];
        if (isset($data['characters'])) {
            foreach ($data['characters'] as $character) {
                if (is_string($character)) {
                    $characters[] = EntityReference::fromUrl($character);
                } elseif (is_array($character) && isset($character['id'])) {
                    $characters[] = new EntityReference(
                        $character['id'],
                        $character['name'] ?? null
                    );
                } elseif ($character instanceof EntityReference) {
                    $characters[] = $character;
                }
            }
        }
        
        return new self(
            $data['id'],
            $data['title'],
            (int)$data['episode_id'],
            $data['opening_crawl'],
            $data['director'],
            $data['producer'],
            $data['release_date'],
            $characters
        );
    }
    
    private static function validate(array $data): void
    {
        $requiredFields = [
            'id' => 'Film ID',
            'title' => 'Film title',
            'episode_id' => 'Episode ID',
            'opening_crawl' => 'Opening crawl',
            'director' => 'Director',
            'producer' => 'Producer',
            'release_date' => 'Release date'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (!isset($data[$field]) || ($field !== 'episode_id' && empty($data[$field]))) {
                throw new InvalidArgumentException("$label is required");
            }
        }
        
        if (!is_numeric($data['episode_id'])) {
            throw new InvalidArgumentException("Episode ID must be numeric");
        }

        if (isset($data['characters']) && !is_array($data['characters'])) {
            throw new InvalidArgumentException("Characters must be an array");
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'episode_id' => $this->episodeId,
            'opening_crawl' => $this->openingCrawl,
            'director' => $this->director,
            'producer' => $this->producer,
            'release_date' => $this->releaseDate,
            'characters' => array_map(fn(EntityReference $character) => $character->toArray(), $this->characters),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEpisodeId(): int
    {
        return $this->episodeId;
    }

    public function getOpeningCrawl(): string
    {
        return $this->openingCrawl;
    }

    public function getDirector(): string
    {
        return $this->director;
    }

    public function getProducer(): string
    {
        return $this->producer;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function withCharacters(array $characters): self
    {
        $this->characters = $characters;
        return $this;
    }
} 