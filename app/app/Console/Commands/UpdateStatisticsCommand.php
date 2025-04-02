<?php

namespace App\Console\Commands;

use App\Application\UseCases\Statistics\UpdateTopQueriesStatisticsUseCase;
use Illuminate\Console\Command;

class UpdateStatisticsCommand extends Command
{
    protected $signature = 'app:update-statistics';


    protected $description = 'Update application statistics';

    public function handle(UpdateTopQueriesStatisticsUseCase $updateTopQueriesStatisticsUseCase)
    {
        $this->info('Updating statistics...');
        
        try {
            $updateTopQueriesStatisticsUseCase->execute();
            $this->info('Statistics updated successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to update statistics: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
} 