<?php

namespace App\Application\UseCases\FetchFilmById;

class FetchFilmByIdInputDTO
{
    public function __construct(
        public readonly string $id
    ) {
    }
} 