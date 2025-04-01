<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Film;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class FilmTest extends TestCase
{
    public function testCreateFilmFromArray(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => ['https://swapi.dev/api/people/1/', 'https://swapi.dev/api/people/2/']
        ];

        $film = Film::fromArray($filmData);

        $this->assertEquals('1', $film->getId());
        $this->assertEquals('A New Hope', $film->getTitle());
        $this->assertEquals(4, $film->getEpisodeId());
        $this->assertEquals('It is a period of civil war...', $film->getOpeningCrawl());
        $this->assertEquals('George Lucas', $film->getDirector());
        $this->assertEquals('Gary Kurtz, Rick McCallum', $film->getProducer());
        $this->assertEquals('1977-05-25', $film->getReleaseDate());
        $this->assertEquals(['https://swapi.dev/api/people/1/', 'https://swapi.dev/api/people/2/'], $film->getCharacters());
    }

    public function testFilmToArray(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => ['https://swapi.dev/api/people/1/', 'https://swapi.dev/api/people/2/']
        ];

        $film = Film::fromArray($filmData);
        $resultArray = $film->toArray();

        $this->assertEquals($filmData['id'], $resultArray['id']);
        $this->assertEquals($filmData['title'], $resultArray['title']);
        $this->assertEquals($filmData['episode_id'], $resultArray['episode_id']);
        $this->assertEquals($filmData['opening_crawl'], $resultArray['opening_crawl']);
        $this->assertEquals($filmData['director'], $resultArray['director']);
        $this->assertEquals($filmData['producer'], $resultArray['producer']);
        $this->assertEquals($filmData['release_date'], $resultArray['release_date']);
        $this->assertEquals($filmData['characters'], $resultArray['characters']);
    }


    
    public function testCreateFilmFromIncompleteArrayThrowsException(): void
    {
        $filmData = [
            'id' => '2',
            'title' => 'The Empire Strikes Back',
            'episode_id' => 5,
        ];

        $this->expectException(InvalidArgumentException::class);
        
        Film::fromArray($filmData);
    }

    public function testCreateFilmWithoutId(): void
    {
        $filmData = [
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => ['https://swapi.dev/api/people/1/']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Film ID is required');
        
        Film::fromArray($filmData);
    }

    public function testCreateFilmWithoutTitle(): void
    {
        $filmData = [
            'id' => '1',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => ['https://swapi.dev/api/people/1/']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Film title is required');
        
        Film::fromArray($filmData);
    }

    public function testCreateFilmWithNonNumericEpisodeId(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 'not a number', 
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => ['https://swapi.dev/api/people/1/']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Episode ID must be numeric');
        
        Film::fromArray($filmData);
    }

    public function testCreateFilmWithInvalidCharactersType(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => 'not an array' 
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Characters must be an array of URLs');
        
        Film::fromArray($filmData);
    }

    public function testCreateFilmWithMissingRequiredFields(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4
        ];

        $this->expectException(InvalidArgumentException::class);
        
        Film::fromArray($filmData);
    }
} 