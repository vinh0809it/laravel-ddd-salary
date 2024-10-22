<?php

namespace Src\SalaryHistory\Application\UseCases\CommandHandlers;

use Src\SalaryHistory\Application\UseCases\Commands\UpdateSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Application\Exceptions\InvalidQueryProvided;
use Src\Shared\Application\ICommand;
use Src\Shared\Application\ICommandHandler;

class UpdateSalaryHistoryHandler implements ICommandHandler
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function handle(ICommand $command)
    {
        if (!$command instanceof UpdateSalaryHistoryCommand) {
            throw new InvalidQueryProvided();
        }

        $this->salaryHistoryService->updateSalaryHistory($command->dto);
    }
}