<?php

namespace App\Application\UseCases\FetchPeople;

class FetchPeopleOutputDTO
{
    public function __construct(
        public readonly array $people,
        public readonly int $total
    ) {
    }
} 