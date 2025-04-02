<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\Statistics\GetTopQueriesStatisticsUseCase;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly GetTopQueriesStatisticsUseCase $getTopQueriesStatisticsUseCase
    ) {
    }

    public function topQueries(): JsonResponse
    {
        try {   
            $statistics = $this->getTopQueriesStatisticsUseCase->execute();
            
            return response()->json([
                'status' => 'success',
                'data' => $statistics->toArray(),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
} 
