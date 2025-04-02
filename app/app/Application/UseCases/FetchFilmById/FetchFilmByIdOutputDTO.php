<?php

namespace App\Application\UseCases\FetchFilmById;

use App\Domain\Entities\Film;

class FetchFilmByIdOutputDTO
{
    public function __construct(
        public readonly Film $film
    ) {
    }
} 