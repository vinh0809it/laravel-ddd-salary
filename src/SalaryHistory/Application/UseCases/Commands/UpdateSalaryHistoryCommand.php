<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class UpdateSalaryHistoryCommand
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function execute(SalaryHistoryDTO $salaryHistoryDTO): void
    {
        $this->salaryHistoryService->updateSalaryHistory($salaryHistoryDTO);
    }
}