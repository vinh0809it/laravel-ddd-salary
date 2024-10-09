<?php

namespace Src\SalaryHistory\Application\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Src\SalaryHistory\Domain\Policies\SalaryHistoryPolicy;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Infrastructure\Repositories\SalaryHistoryRepository;
use Src\SalaryHistory\Domain\Rules\UserExistsRule;
use Src\User\Domain\Services\UserService;

class SalaryHistoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ISalaryHistoryRepository::class,
            SalaryHistoryRepository::class
        );

        // Rule injection
        $this->app->singleton(UserExistsRule::class, function ($app) {
            return new UserExistsRule($app->make(UserService::class));
        });
    }

    public function boot() 
    {   
        // Define gate
        Gate::define("salary_history.get", [SalaryHistoryPolicy::class, 'get']);
        Gate::define("salary_history.store", [SalaryHistoryPolicy::class, 'store']);
        Gate::define("salary_history.update", [SalaryHistoryPolicy::class, 'update']);
        Gate::define("salary_history.delete", [SalaryHistoryPolicy::class, 'delete']);
    }
}