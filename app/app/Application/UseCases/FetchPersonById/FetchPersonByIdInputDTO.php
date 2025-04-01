<?php

namespace App\Application\UseCases\FetchPersonById;

class FetchPersonByIdInputDTO
{
    public function __construct(
        public readonly string $id
    ) {
    }
} 