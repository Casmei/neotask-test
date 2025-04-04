<?php

namespace App\Providers;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\Repositories\MusicRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MusicRepositoryInterface::class,
            MusicRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
