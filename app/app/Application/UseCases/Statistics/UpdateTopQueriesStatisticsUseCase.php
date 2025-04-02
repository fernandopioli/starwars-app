<?php

namespace App\Application\UseCases\Statistics;

use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;

class UpdateTopQueriesStatisticsUseCase
{
    public function __construct(
        private readonly StatisticsRepositoryInterface $statisticsRepository
    ) {
    }

    public function execute(int $limit = 5): void
    {
        $this->statisticsRepository->updateTopQueriesStatistics($limit);
    }
} 