<?php

namespace App\Application\UseCases\FetchFilms;

class FetchFilmsOutputDTO
{
    public function __construct(
        public readonly array $films,
        public readonly int $total
    ) {
    }
} 