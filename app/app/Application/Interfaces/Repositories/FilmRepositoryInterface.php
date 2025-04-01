<?php

namespace App\Application\Interfaces\Repositories;

use App\Domain\Entities\Film;

interface FilmRepositoryInterface
{

    public function findAll(?string $query): array;
    
    public function findById(string $id): ?Film;
} 