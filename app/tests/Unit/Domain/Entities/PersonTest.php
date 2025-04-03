<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Person;
use App\Domain\ValueObjects\EntityReference;
use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testCreatePersonFromArray(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => ['https://swapi.dev/api/films/1/', 'https://swapi.dev/api/films/2/']
        ];

        $person = Person::fromArray($personData);

        $this->assertEquals('1', $person->getId());
        $this->assertEquals('Luke Skywalker', $person->getName());
        $this->assertEquals('172', $person->getHeight());
        $this->assertEquals('77', $person->getMass());
        $this->assertEquals('blond', $person->getHairColor());
        $this->assertEquals('fair', $person->getSkinColor());
        $this->assertEquals('blue', $person->getEyeColor());
        $this->assertEquals('19BBY', $person->getBirthYear());
        $this->assertEquals('male', $person->getGender());
        
        $this->assertCount(2, $person->getFilms());
        $this->assertContainsOnlyInstancesOf(EntityReference::class, $person->getFilms());
        $this->assertEquals('1', $person->getFilms()[0]->id);
        $this->assertEquals('2', $person->getFilms()[1]->id);
    }

    public function testPersonToArray(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => ['https://swapi.dev/api/films/1/', 'https://swapi.dev/api/films/2/']
        ];

        $person = Person::fromArray($personData);
        $resultArray = $person->toArray();

        $this->assertEquals($personData['id'], $resultArray['id']);
        $this->assertEquals($personData['name'], $resultArray['name']);
        $this->assertEquals($personData['height'], $resultArray['height']);
        $this->assertEquals($personData['mass'], $resultArray['mass']);
        $this->assertEquals($personData['hair_color'], $resultArray['hair_color']);
        $this->assertEquals($personData['skin_color'], $resultArray['skin_color']);
        $this->assertEquals($personData['eye_color'], $resultArray['eye_color']);
        $this->assertEquals($personData['birth_year'], $resultArray['birth_year']);
        $this->assertEquals($personData['gender'], $resultArray['gender']);
        
        $this->assertIsArray($resultArray['films']);
        $this->assertCount(2, $resultArray['films']);
        $this->assertEquals('1', $resultArray['films'][0]['id']);
        $this->assertEquals('2', $resultArray['films'][1]['id']);
    }

    public function testCreatePersonFromIncompleteArrayWithoutRequiredFields(): void
    {
        $personData = [
            'height' => '180',
            'mass' => '80'
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Person ID is required');
        
        Person::fromArray($personData);
    }

    public function testCreatePersonWithoutId(): void
    {
        $personData = [
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => ['https://swapi.dev/api/films/1/']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Person ID is required');
        
        Person::fromArray($personData);
    }

    public function testCreatePersonWithoutName(): void
    {
        $personData = [
            'id' => '1',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => ['https://swapi.dev/api/films/1/']
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Person name is required');
        
        Person::fromArray($personData);
    }
    
    public function testCreatePersonWithInvalidFilmsType(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => 'not an array' 
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Films must be an array');
        
        Person::fromArray($personData);
    }
    
    public function testCreatePersonWithFilmsAsEntityReferences(): void
    {
 
        $entityRef1 = new \App\Domain\ValueObjects\EntityReference('1', 'A New Hope');
        $entityRef2 = new \App\Domain\ValueObjects\EntityReference('2', 'The Empire Strikes Back');
        
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => [$entityRef1, $entityRef2]
        ];

        $person = Person::fromArray($personData);
        
        $this->assertCount(2, $person->getFilms());
        $this->assertContainsOnlyInstancesOf(EntityReference::class, $person->getFilms());
        
        $this->assertSame($entityRef1, $person->getFilms()[0]);
        $this->assertSame($entityRef2, $person->getFilms()[1]);
        
        $this->assertEquals('1', $person->getFilms()[0]->id);
        $this->assertEquals('A New Hope', $person->getFilms()[0]->name);
        $this->assertEquals('2', $person->getFilms()[1]->id);
        $this->assertEquals('The Empire Strikes Back', $person->getFilms()[1]->name);
    }
    
    public function testCreatePersonWithFilmsAsArrays(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => [
                ['id' => '1', 'name' => 'A New Hope'],
                ['id' => '2', 'name' => 'The Empire Strikes Back']
            ]
        ];

        $person = Person::fromArray($personData);
        
        $this->assertCount(2, $person->getFilms());
        $this->assertContainsOnlyInstancesOf(\App\Domain\ValueObjects\EntityReference::class, $person->getFilms());
        
        $this->assertEquals('1', $person->getFilms()[0]->id);
        $this->assertEquals('A New Hope', $person->getFilms()[0]->name);
        $this->assertEquals('2', $person->getFilms()[1]->id);
        $this->assertEquals('The Empire Strikes Back', $person->getFilms()[1]->name);
    }
    
    public function testCreatePersonWithUnsupportedFilmFormat(): void
    {
        $unsupportedFilm = new \stdClass();
        $unsupportedFilm->title = 'Return of the Jedi';
        
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => [$unsupportedFilm]
        ];

        // The Person class skips unsupported film formats rather than throwing an exception,
        // so we should test that the film was ignored
        $person = Person::fromArray($personData);
        
        // Verify that the unsupported film was ignored
        $this->assertCount(0, $person->getFilms());
    }

    public function testWithFilmsMethod(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'films' => []
        ];

        $person = Person::fromArray($personData);
        
        // Initially no films
        $this->assertCount(0, $person->getFilms());
        
        // Add films
        $newFilms = [
            new EntityReference('1', 'A New Hope'),
            new EntityReference('2', 'The Empire Strikes Back'),
            new EntityReference('3', 'Return of the Jedi')
        ];
        
        $updatedPerson = $person->withFilms($newFilms);
        
        // Check that it's the same instance (fluent interface)
        $this->assertSame($person, $updatedPerson);
        
        // Check that films were updated
        $this->assertCount(3, $person->getFilms());
        $this->assertEquals('1', $person->getFilms()[0]->id);
        $this->assertEquals('A New Hope', $person->getFilms()[0]->name);
        $this->assertEquals('3', $person->getFilms()[2]->id);
        $this->assertEquals('Return of the Jedi', $person->getFilms()[2]->name);
    }

    public function testWithFilmsReplacesPreviousFilms(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'films' => ['https://swapi.dev/api/films/1/']
        ];

        $person = Person::fromArray($personData);
        
        // Initially one film
        $this->assertCount(1, $person->getFilms());
        $this->assertEquals('1', $person->getFilms()[0]->id);
        
        // Replace with new films
        $newFilms = [
            new EntityReference('4', 'The Phantom Menace'),
            new EntityReference('5', 'Attack of the Clones')
        ];
        
        $person->withFilms($newFilms);
        
        // Check that films were replaced, not added
        $this->assertCount(2, $person->getFilms());
        $this->assertEquals('4', $person->getFilms()[0]->id);
        $this->assertEquals('5', $person->getFilms()[1]->id);
    }

    public function testWithFilmsWithEmptyArray(): void
    {
        $personData = [
            'id' => '1',
            'name' => 'Luke Skywalker',
            'films' => ['https://swapi.dev/api/films/1/', 'https://swapi.dev/api/films/2/']
        ];

        $person = Person::fromArray($personData);
        
        // Initially two films
        $this->assertCount(2, $person->getFilms());
        
        // Replace with empty array
        $person->withFilms([]);
        
        // Check that films were cleared
        $this->assertCount(0, $person->getFilms());
        $this->assertEmpty($person->getFilms());
    }
}
