<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HelloWorldCommand extends Command
{
    protected $signature = 'app:hello-world';
    protected $description = 'Demonstrate a working scheduled command';

    public function handle()
    {
        Log::info('Hello World! Scheduled command executed at ' . now()->toDateTimeString());
        $this->info('Hello World command executed successfully!');
        
        return Command::SUCCESS;
    }
} 