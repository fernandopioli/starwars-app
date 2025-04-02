<?php

namespace App\Application\UseCases\FetchPersonById;

use App\Domain\Entities\Person;

class FetchPersonByIdOutputDTO
{

    public function __construct(
        public readonly Person $person
    ) {
    }
} 