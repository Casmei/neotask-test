<?php

namespace App\Providers;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\Repositories\Interfaces\UserRepositoryInterface;
use App\Domain\Repositories\MusicRepository;
use App\Domain\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Http\Resources\Json\JsonResource;

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

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()->withDocumentTransformers(function (
            OpenApi $openApi
        ) {
            $openApi->secure(SecurityScheme::http("bearer", "JWT"));
        });
        JsonResource::withoutWrapping();
    }
}
