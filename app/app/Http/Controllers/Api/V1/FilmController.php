<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\FetchFilms\FetchFilmsInputDTO;
use App\Application\UseCases\FetchFilms\FetchFilmsUseCase;
use App\Application\UseCases\FetchFilmById\FetchFilmByIdInputDTO;
use App\Application\UseCases\FetchFilmById\FetchFilmByIdUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilmController extends Controller
{
    public function __construct(
        private readonly FetchFilmsUseCase $fetchFilmsUseCase,
        private readonly FetchFilmByIdUseCase $fetchFilmByIdUseCase
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            
            $output = $this->fetchFilmsUseCase->execute(
                new FetchFilmsInputDTO(searchQuery: $query)
            );

            return response()->json([
                'status' => 'success',
                'total' => $output->total,
                'data' => $output->films
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $output = $this->fetchFilmByIdUseCase->execute(
                new FetchFilmByIdInputDTO(id: $id)
            );

            if ($output->film === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Film not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'data' => $output->film->toArray()
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function handleError(\Exception $e): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], $e->getMessage() === "Film not found" ? Response::HTTP_NOT_FOUND : $e->getCode());
    }
} 