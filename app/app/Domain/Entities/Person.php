<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class Person
{
    private string $id;
    private string $name;
    private ?string $height;
    private ?string $mass;
    private ?string $hairColor;
    private ?string $skinColor;
    private ?string $eyeColor;
    private ?string $birthYear;
    private ?string $gender;
    private array $films;

    private function __construct(
        string $id,
        string $name,
        ?string $height,
        ?string $mass,
        ?string $hairColor,
        ?string $skinColor,
        ?string $eyeColor,
        ?string $birthYear,
        ?string $gender,
        array $films,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->height = $height;
        $this->mass = $mass;
        $this->hairColor = $hairColor;
        $this->skinColor = $skinColor;
        $this->eyeColor = $eyeColor;
        $this->birthYear = $birthYear;
        $this->gender = $gender;
        $this->films = $films;
    }

    public static function fromArray(array $data): self
    {
        self::validate($data);
        
        return new self(
            $data['id'],
            $data['name'],
            $data['height'] ?? null,
            $data['mass'] ?? null,
            $data['hair_color'] ?? null,
            $data['skin_color'] ?? null,
            $data['eye_color'] ?? null,
            $data['birth_year'] ?? null,
            $data['gender'] ?? null,
            $data['films'] ?? []
        );
    }


    private static function validate(array $data): void
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new InvalidArgumentException("Person ID is required");
        }
        
        if (!isset($data['name']) || empty($data['name'])) {
            throw new InvalidArgumentException("Person name is required");
        }

         if (isset($data['films']) && !is_array($data['films'])) {
            throw new InvalidArgumentException("Films must be an array of URLs");
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'height' => $this->height,
            'mass' => $this->mass,
            'hair_color' => $this->hairColor,
            'skin_color' => $this->skinColor,
            'eye_color' => $this->eyeColor,
            'birth_year' => $this->birthYear,
            'gender' => $this->gender,
            'films' => $this->films,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function getMass(): ?string
    {
        return $this->mass;
    }

    public function getHairColor(): ?string
    {
        return $this->hairColor;
    }

    public function getSkinColor(): ?string
    {
        return $this->skinColor;
    }

    public function getEyeColor(): ?string
    {
        return $this->eyeColor;
    }

    public function getBirthYear(): ?string
    {
        return $this->birthYear;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getFilms(): array
    {
        return $this->films;
    }
}