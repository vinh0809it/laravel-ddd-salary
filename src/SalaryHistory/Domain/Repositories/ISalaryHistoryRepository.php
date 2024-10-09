<?php

namespace Src\SalaryHistory\Domain\Repositories;

use Src\SalaryHistory\Domain\Model\SalaryHistory;

interface ISalaryHistoryRepository
{
    public function existsForUserInYear(string $userId, int $year): bool;
    public function storeSalaryHistory(SalaryHistory $salaryHistory): SalaryHistory;
}
