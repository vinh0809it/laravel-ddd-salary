<?php

namespace Src\SalaryHistory\Domain\Repositories;

use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Domain\IBaseRepository;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;

interface ISalaryHistoryRepository extends IBaseRepository
{
    public function existsForUserInYear(string $userId, int $year): bool;
    public function getSalaryHistories(SalaryHistoryFilterDTO $filterDTO, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO;
    public function findSalaryHistoryById(string $id): ?SalaryHistory;
    public function storeSalaryHistory(SalaryHistory $salaryHistory): SalaryHistory;
    public function updateSalaryHistory(SalaryHistory $salaryHistory): void;
}
