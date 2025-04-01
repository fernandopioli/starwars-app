<?php

namespace App\Application\UseCases\FetchFilms;

use App\Application\Interfaces\Repositories\FilmRepositoryInterface;

class FetchFilmsUseCase
{
    public function __construct(
        private readonly FilmRepositoryInterface $filmRepository
    ) {
    }

    public function execute(FetchFilmsInputDTO $input): FetchFilmsOutputDTO
    {
        $films = $this->filmRepository->findAll($input->searchQuery);

        $filmsData = array_map(
            fn($film) => $film->toArray(),
            $films
        );

        return new FetchFilmsOutputDTO(
            films: $filmsData,
            total: count($filmsData)
        );
    }
} 