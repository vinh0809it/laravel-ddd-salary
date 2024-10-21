<?php

namespace Src\User\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Src\User\Infrastructure\Listeners\StoreUserListener;
use Src\User\Domain\Events\StoreUserEvent;
use Src\User\Domain\Policies\UserPolicy;
use Src\User\Domain\Repositories\IUserRepository;
use Src\User\Domain\Rules\EmailUniqueRule;
use Src\User\Infrastructure\Repositories\UserRepository;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            IUserRepository::class,
            UserRepository::class
        );

        // Rule injection
        $this->app->singleton(EmailUniqueRule::class, function ($app) {
            return new EmailUniqueRule($app->make(IUserRepository::class));
        });
    }

    public function boot() 
    {   
        // Define gate
        Gate::define("user.get", [UserPolicy::class, 'get']);
        Gate::define("user.store", [UserPolicy::class, 'store']);
        Gate::define("user.update", [UserPolicy::class, 'update']);
        Gate::define("user.delete", [UserPolicy::class, 'delete']);

        // Register event listeners
        Event::listen(
            StoreUserEvent::class,
            StoreUserListener::class
        );
    }
}