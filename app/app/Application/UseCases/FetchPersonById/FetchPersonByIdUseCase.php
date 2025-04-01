<?php

namespace App\Application\UseCases\FetchPersonById;

use App\Application\Interfaces\Repositories\PersonRepositoryInterface;

class FetchPersonByIdUseCase
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository
    ) {
    }

    public function execute(FetchPersonByIdInputDTO $input): FetchPersonByIdOutputDTO
    {
        $person = $this->personRepository->findById($input->id);
        
        if (!$person) {
            throw new \Exception('Person not found');
        }

        return new FetchPersonByIdOutputDTO(
            person: $person
        );
    }
} 