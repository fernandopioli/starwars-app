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
        
        $this->assertCount(2, $film->getCharacters());
        $this->assertContainsOnlyInstancesOf(\App\Domain\ValueObjects\EntityReference::class, $film->getCharacters());
        $this->assertEquals('1', $film->getCharacters()[0]->id);
        $this->assertEquals('2', $film->getCharacters()[1]->id);
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
        
        $this->assertIsArray($resultArray['characters']);
        $this->assertCount(2, $resultArray['characters']);
        $this->assertEquals('1', $resultArray['characters'][0]['id']);
        $this->assertEquals('2', $resultArray['characters'][1]['id']);
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
        $this->expectExceptionMessage('Characters must be an array');
        
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

    public function testCreateFilmWithCharactersAsArrays(): void
    {
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => [
                ['id' => '1', 'name' => 'Luke Skywalker'],
                ['id' => '2', 'name' => 'C-3PO']
            ]
        ];

        $film = Film::fromArray($filmData);
        
        $this->assertCount(2, $film->getCharacters());
        $this->assertContainsOnlyInstancesOf(\App\Domain\ValueObjects\EntityReference::class, $film->getCharacters());
        $this->assertEquals('1', $film->getCharacters()[0]->id);
        $this->assertEquals('Luke Skywalker', $film->getCharacters()[0]->name);
        $this->assertEquals('2', $film->getCharacters()[1]->id);
        $this->assertEquals('C-3PO', $film->getCharacters()[1]->name);
    }
    
    public function testCreateFilmWithCharactersAsEntityReferences(): void
    {
        $entityRef1 = new \App\Domain\ValueObjects\EntityReference('1', 'Luke Skywalker');
        $entityRef2 = new \App\Domain\ValueObjects\EntityReference('2', 'C-3PO');
        
        $filmData = [
            'id' => '1',
            'title' => 'A New Hope',
            'episode_id' => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director' => 'George Lucas',
            'producer' => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'characters' => [$entityRef1, $entityRef2]
        ];

        $film = Film::fromArray($filmData);
        
        $this->assertCount(2, $film->getCharacters());
        $this->assertContainsOnlyInstancesOf(\App\Domain\ValueObjects\EntityReference::class, $film->getCharacters());
        $this->assertEquals('1', $film->getCharacters()[0]->id);
        $this->assertEquals('Luke Skywalker', $film->getCharacters()[0]->name);
        $this->assertEquals('2', $film->getCharacters()[1]->id);
        $this->assertEquals('C-3PO', $film->getCharacters()[1]->name);
        
        $this->assertSame($entityRef1, $film->getCharacters()[0]);
        $this->assertSame($entityRef2, $film->getCharacters()[1]);
    }
} 