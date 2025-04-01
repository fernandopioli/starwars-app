<?php

namespace App\Application\UseCases\FetchFilmById;

use App\Application\Interfaces\Repositories\FilmRepositoryInterface;

class FetchFilmByIdUseCase
{
    public function __construct(
        private readonly FilmRepositoryInterface $filmRepository
    ) {
    }

    public function execute(FetchFilmByIdInputDTO $input): FetchFilmByIdOutputDTO
    {
        $film = $this->filmRepository->findById($input->id);

        if (!$film) {
            throw new \Exception('Film not found');
        }
        
        return new FetchFilmByIdOutputDTO(
            film: $film
        );
    }
} 