<?php

namespace App\Application\UseCases\Statistics;

use App\Domain\Entities\TopQueriesStatistic;
use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;

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