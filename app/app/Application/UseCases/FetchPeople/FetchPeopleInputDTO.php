<?php

namespace App\Application\UseCases\FetchPeople;

class FetchPeopleInputDTO
{
    public function __construct(
        public readonly ?string $searchQuery = null
    ) {
    }
} 