<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Carbon\Carbon;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class StoreSalaryHistoryCommand
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService
    )
    {}

    public function execute(StoreSalaryHistoryDTO $dto): SalaryHistory
    {
        $currentYear = Carbon::parse($dto->onDate)->format('Y');

        if (!$this->salaryHistoryService->canStoreSalaryHistory($dto->userId, $currentYear)) {
            throw new UserHasSalaryRecordInYearException();
        }

        return $this->salaryHistoryService->storeSalaryHistory($dto);
    }
}