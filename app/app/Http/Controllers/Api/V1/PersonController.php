<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\FetchPeople\FetchPeopleInputDTO;
use App\Application\UseCases\FetchPeople\FetchPeopleUseCase;
use App\Application\UseCases\FetchPersonById\FetchPersonByIdInputDTO;
use App\Application\UseCases\FetchPersonById\FetchPersonByIdUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends Controller
{
    public function __construct(
        private readonly FetchPeopleUseCase $fetchPeopleUseCase,
        private readonly FetchPersonByIdUseCase $fetchPersonByIdUseCase
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            
            $output = $this->fetchPeopleUseCase->execute(
                new FetchPeopleInputDTO(searchQuery: $query)
            );

            return response()->json([
                'status' => 'success',
                'total' => $output->total,
                'data' => $output->people
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $output = $this->fetchPersonByIdUseCase->execute(
                new FetchPersonByIdInputDTO(id: $id)
            );

            if ($output->person === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Person not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'data' => $output->person->toArray()
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
        ], $e->getMessage() === "Person not found" ? Response::HTTP_NOT_FOUND : $e->getCode());
    }
} 