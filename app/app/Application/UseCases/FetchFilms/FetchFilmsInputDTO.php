<?php

namespace App\Application\UseCases\FetchFilms;

class FetchFilmsInputDTO
{
    public function __construct(
        public readonly ?string $searchQuery = null,
    ) {
    }
} 