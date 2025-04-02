<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\GetTopQueriesStatisticsUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly GetTopQueriesStatisticsUseCase $getTopQueriesStatisticsUseCase
    ) {
    }

    public function topQueries(): JsonResponse
    {
        $statistics = $this->getTopQueriesStatisticsUseCase->execute();
        
        return response()->json([
            'data' => $statistics->toArray(),
            'message' => 'Statistics retrieved successfully'
        ]);
    }
} 