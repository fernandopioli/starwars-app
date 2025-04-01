<?php

namespace App\Application\UseCases\FetchPeople;

use App\Application\Interfaces\Repositories\PersonRepositoryInterface;

class FetchPeopleUseCase
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository
    ) {
    }

    public function execute(FetchPeopleInputDTO $input): FetchPeopleOutputDTO
    {
        $people = $this->personRepository->findAll($input->searchQuery);

        $peopleData = array_map(
            fn($person) => $person->toArray(),
            $people
        );

        return new FetchPeopleOutputDTO(
            people: $peopleData,
            total: count($peopleData)
        );
    }
} 