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

    public function execute(UpdateSalaryHistoryDTO $updateSalaryHistoryDTO): void
    {
        $this->salaryHistoryService->updateSalaryHistory($updateSalaryHistoryDTO);
    }
}