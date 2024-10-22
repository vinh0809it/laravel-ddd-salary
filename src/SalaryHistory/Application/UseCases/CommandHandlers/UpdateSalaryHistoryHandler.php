<?php

namespace Src\SalaryHistory\Application\UseCases\CommandHandlers;

use Src\SalaryHistory\Application\UseCases\Commands\UpdateSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class UpdateSalaryHistoryHandler
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function handle(UpdateSalaryHistoryCommand $command): void
    {
        $this->salaryHistoryService->updateSalaryHistory($command->dto);
    }
}