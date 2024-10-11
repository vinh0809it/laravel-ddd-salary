<?php

namespace Src\SalaryHistory\Domain\Repositories;

use Src\Common\Domain\IBaseRepository;
use Src\SalaryHistory\Domain\Model\SalaryHistory;

interface ISalaryHistoryRepository extends IBaseRepository
{
    public function existsForUserInYear(string $userId, int $year): bool;
    public function findSalaryHistoryById(string $id): SalaryHistory;
    public function storeSalaryHistory(SalaryHistory $salaryHistory): SalaryHistory;
    public function updateSalaryHistory(SalaryHistory $salaryHistory): void;
}
