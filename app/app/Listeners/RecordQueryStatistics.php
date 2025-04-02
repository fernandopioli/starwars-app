<?php

namespace App\Listeners;

use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;
use App\Events\QueryPerformed;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordQueryStatistics implements ShouldQueue
{
    public function __construct(
        private readonly StatisticsRepositoryInterface $statisticsRepository
    ) {
    }

    public function handle(QueryPerformed $event): void
    {
        $this->statisticsRepository->recordQuery($event->query, $event->type);
    }
} 