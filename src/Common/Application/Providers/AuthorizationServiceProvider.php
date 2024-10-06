<?php

namespace Src\Common\Application\Providers;


use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            \Src\Common\Domain\Services\AuthorizationServiceInterface::class,
            \Src\Common\Infrastructure\Services\AuthorizationService::class
        );
    }
}