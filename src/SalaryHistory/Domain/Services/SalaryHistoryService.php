<?php

namespace Src\SalaryHistory\Domain\Services;

use Carbon\Carbon;
use Throwable;
use Src\Common\Application\DTOs\PageMetaDTO;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;

class SalaryHistoryService
{
    public function __construct(
        private SalaryHistoryFactory $salaryHistoryFactory,
        private ISalaryHistoryRepository $salaryHistoryRepository
    ) {}

    /**
     * @param SalaryHistoryFilterDTO $filter
     * @param PageMetaDTO $pageMetaDTO
     * 
     * @return SalaryHistoryWithPageMetaDTO
     */
    public function getSalaryHistories(SalaryHistoryFilterDTO $filter, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO
    {
        try {
            return $this->salaryHistoryRepository->getSalaryHistories($filter, $pageMetaDTO);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to fetch salary histories: ' . $e->getMessage());
        }
    }

    /**
     * @param string $userId
     * @param int $year
     * 
     * @return bool
     */
    public function canStoreSalaryHistory(string $userId, int $year): bool
    {
        return !$this->salaryHistoryRepository->existsForUserInYear($userId, $year);
    }

    /**
     * @param SalaryHistoryDTO $salaryHistoryDTO
     * 
     * @return SalaryHistory
     */
    public function storeSalaryHistory(SalaryHistoryDTO $salaryHistoryDTO): SalaryHistory
    {
        $currentYear = Carbon::parse($salaryHistoryDTO->onDate)->format('Y');

        $canStoreSalaryHistory = $this->canStoreSalaryHistory(
            $salaryHistoryDTO->userId, 
            $currentYear
        );

        if (!$canStoreSalaryHistory) {
            throw new UserHasSalaryRecordInYearException();
        }
 
        $salaryHistory = $this->salaryHistoryFactory->create(
            $salaryHistoryDTO->id,
            $salaryHistoryDTO->userId,
            $salaryHistoryDTO->onDate,
            $salaryHistoryDTO->salary,
            $salaryHistoryDTO->note
        );

        try {
            return $this->salaryHistoryRepository->storeSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store salary history: ' . $e->getMessage());
        }
    }

    /**
     * @param SalaryHistoryDTO $salaryHistoryDTO
     * 
     * @return void
     */
    public function updateSalaryHistory(SalaryHistoryDTO $salaryHistoryDTO): void
    {
        $salaryHistory = $this->salaryHistoryRepository->findSalaryHistoryById($salaryHistoryDTO->id);

        if($salaryHistoryDTO->onDate) {
            $salaryHistory->setDate(new Date($salaryHistoryDTO->onDate));
        }

        if($salaryHistoryDTO->salary) {
            $salaryHistory->setSalary(new Salary($salaryHistoryDTO->salary));
        }

        try {
            $this->salaryHistoryRepository->updateSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to update salary history: ' . $e->getMessage());
        }
    }
}