<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class StoreSalaryHistoryCommand
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function execute(SalaryHistoryDTO $salaryHistoryDTO): SalaryHistory
    {
        return $this->salaryHistoryService->storeSalaryHistory($salaryHistoryDTO);
    }
}