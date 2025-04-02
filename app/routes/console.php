<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command(\App\Console\Commands\UpdateStatisticsCommand::class)->everyFiveMinutes()->appendOutputTo(storage_path('logs/scheduler.log'));
