<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shared\Domain\DomainEventDispatcher;
use Src\Shared\Infrastructure\LaravelEventDispatcher;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            DomainEventDispatcher::class,
            LaravelEventDispatcher::class
        );
    }
}