<?php

namespace App\Application\UseCases;

use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;
use App\Domain\Entities\TopQueriesStatistic;

class GetTopQueriesStatisticsUseCase
{
    public function __construct(
        private readonly StatisticsRepositoryInterface $statisticsRepository
    ) {
    }

    public function execute(int $limit = 5): TopQueriesStatistic
    {
        return $this->statisticsRepository->getTopQueries($limit);
    }
} 