<?php

namespace Src\SalaryHistory\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Src\SalaryHistory\Application\Bus\QueryBus;
use Src\SalaryHistory\Application\Listeners\CreateSalaryHistoryForUser;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\SalaryHistory\Application\UseCases\CommandHandlers\StoreSalaryHistoryHandler;
use Src\SalaryHistory\Application\UseCases\CommandHandlers\UpdateSalaryHistoryHandler;
use Src\SalaryHistory\Application\UseCases\Commands\UpdateSalaryHistoryCommand;
use Src\SalaryHistory\Application\UseCases\Queries\GetSalaryHistoriesQuery;
use Src\SalaryHistory\Application\UseCases\QueryHandlers\GetSalaryHistoriesQueryHandler;
use Src\SalaryHistory\Infrastructure\Policies\SalaryHistoryPolicy;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Infrastructure\Repositories\SalaryHistoryRepository;
use Src\SalaryHistory\Domain\Rules\UserExistsRule;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Infrastructure\Buses\CommandBus;
use Src\SalaryHistory\Infrastructure\Services\UserDomainService;
use Src\User\Domain\Events\StoreUserEvent;

class SalaryHistoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ISalaryHistoryRepository::class,
            SalaryHistoryRepository::class
        );

        // Domain Service Binding
        $this->app->singleton(
            IUserDomainService::class,
            UserDomainService::class
        );

        // Command bus Binding
        $this->app->singleton(CommandBus::class, function ($app) {
            $commandBus = new CommandBus();
            
            $commandBus->register(StoreSalaryHistoryCommand::class, StoreSalaryHistoryHandler::class);
            $commandBus->register(UpdateSalaryHistoryCommand::class, UpdateSalaryHistoryHandler::class);
            return $commandBus;
        });

        // Query Bus Binding
        $this->app->singleton(QueryBus::class, function ($app) {
            $queryBus = new QueryBus();
    
            $queryBus->register(
                GetSalaryHistoriesQuery::class, 
                new GetSalaryHistoriesQueryHandler($app->make(SalaryHistoryService::class))
            );
            
            return $queryBus;
        });

        // Rule Binding
        $this->app->singleton(UserExistsRule::class, function ($app) {
            return new UserExistsRule($app->make(IUserDomainService::class));
        });
    }

    public function boot() 
    {   
        // Define gate
        Gate::define("salary_history.get", [SalaryHistoryPolicy::class, 'get']);
        Gate::define("salary_history.store", [SalaryHistoryPolicy::class, 'store']);
        Gate::define("salary_history.update", [SalaryHistoryPolicy::class, 'update']);
        Gate::define("salary_history.delete", [SalaryHistoryPolicy::class, 'delete']);

        // Event Binding
        Event::listen(
            StoreUserEvent::class,
            CreateSalaryHistoryForUser::class
        );
    }
}