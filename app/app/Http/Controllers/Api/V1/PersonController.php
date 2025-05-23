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
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        
        if ($e->getMessage() === "Person not found") {
            $statusCode = Response::HTTP_NOT_FOUND;
        } else if ($e instanceof \Illuminate\Database\QueryException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        } else if (is_int($e->getCode()) && $e->getCode() >= 400 && $e->getCode() < 600) {
            $statusCode = $e->getCode();
        }
        
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], $statusCode);
    }
} 