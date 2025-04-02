<?php

namespace App\Providers;

use App\Events\QueryPerformed;
use App\Listeners\RecordQueryStatistics;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        QueryPerformed::class => [
            RecordQueryStatistics::class,
        ],
    ];


    public function boot(): void
    {
        //
    }
} 