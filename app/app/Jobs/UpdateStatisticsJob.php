<?php

namespace App\Jobs;

use App\Application\UseCases\UpdateTopQueriesStatisticsUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(UpdateTopQueriesStatisticsUseCase $updateTopQueriesStatisticsUseCase): void
    {
        try {
            Log::info('Starting to update statistics via job');
            $updateTopQueriesStatisticsUseCase->execute();
            Log::info('Statistics updated successfully via job');
        } catch (\Exception $e) {
            Log::error('Error updating statistics via job: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
} 