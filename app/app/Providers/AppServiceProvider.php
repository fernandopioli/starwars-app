<?php

namespace App\Providers;

use App\Application\Interfaces\Repositories\FilmRepositoryInterface;
use App\Application\Interfaces\Repositories\PersonRepositoryInterface;
use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;
use App\Infrastructure\Http\GuzzleHttpClient;
use App\Infrastructure\Http\HttpClientInterface;
use App\Infrastructure\Repositories\DatabaseStatisticsRepository;
use App\Infrastructure\Repositories\StarWarsFilmRepository;
use App\Infrastructure\Repositories\StarWarsPersonRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HttpClientInterface::class, GuzzleHttpClient::class);

        $this->app->bind(FilmRepositoryInterface::class, StarWarsFilmRepository::class);
        $this->app->bind(PersonRepositoryInterface::class, StarWarsPersonRepository::class);
        $this->app->bind(StatisticsRepositoryInterface::class, DatabaseStatisticsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
