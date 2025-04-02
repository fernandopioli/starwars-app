<?php

namespace App\Application\Interfaces\Repositories;

use App\Domain\Entities\TopQueriesStatistic;

interface StatisticsRepositoryInterface
{

    public function getTopQueries(int $limit = 5): TopQueriesStatistic;

    public function recordQuery(string $query, string $type): void;

    public function updateTopQueriesStatistics(int $limit = 5): void;
} 
