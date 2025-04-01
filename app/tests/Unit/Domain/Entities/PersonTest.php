<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Person;
use App\Domain\Entities\Film;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

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
        $this->assertEquals(['https://swapi.dev/api/films/1/', 'https://swapi.dev/api/films/2/'], $person->getFilms());
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
        $this->assertEquals($personData['films'], $resultArray['films']);
    }

    public function testCreatePersonFromIncompleteArrayWithoutRequiredFields(): void
    {
        $personData = [
            'height' => '180',
            'mass' => '80'
        ];

        $this->expectException(InvalidArgumentException::class);
        
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
        $this->expectExceptionMessage('Films must be an array of URLs');
        
        Person::fromArray($personData);
    }
}
