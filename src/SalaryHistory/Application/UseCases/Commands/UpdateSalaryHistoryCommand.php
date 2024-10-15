<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class UpdateSalaryHistoryCommand
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function execute(UpdateSalaryHistoryDTO $dto): void
    {
        $this->salaryHistoryService->updateSalaryHistory($dto);
    }
}