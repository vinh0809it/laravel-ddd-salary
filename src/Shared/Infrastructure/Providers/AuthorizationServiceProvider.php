<?php

namespace Src\Shared\Infrastructure\Providers;


use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            \Src\Shared\Domain\Services\AuthorizationServiceInterface::class,
            \Src\Shared\Infrastructure\Services\AuthorizationService::class
        );
    }
}