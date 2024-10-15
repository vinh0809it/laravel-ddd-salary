<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class StoreSalaryHistoryCommand
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function execute(StoreSalaryHistoryDTO $dto): SalaryHistory
    {
        // Dispatch event here
        return $this->salaryHistoryService->storeSalaryHistory($dto);
    }
}