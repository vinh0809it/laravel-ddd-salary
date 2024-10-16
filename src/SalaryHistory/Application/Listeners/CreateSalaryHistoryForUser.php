<?php

namespace Src\SalaryHistory\Application\Listeners;

use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\User\Domain\Events\StoreUserEvent;

class CreateSalaryHistoryForUser
{
    public function __construct(private SalaryHistoryService $salaryHistoryService)
    {}

    public function handle(StoreUserEvent $event)
    {
        $this->salaryHistoryService->storeSalaryHistoryForNewUser($event->userId);
    }
}