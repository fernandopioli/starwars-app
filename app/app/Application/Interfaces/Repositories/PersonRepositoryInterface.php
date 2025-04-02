<?php

namespace App\Application\Interfaces\Repositories;

use App\Domain\Entities\Person;

interface PersonRepositoryInterface
{

    public function findAll(?string $query): array;
    
    public function findById(string $id, bool $withEnrichment = true): ?Person;
} 