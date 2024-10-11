<?php

namespace Src\SalaryHistory\Domain\Services;

use Carbon\Carbon;
use DomainException;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\Model\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Throwable;

class SalaryHistoryService
{
    public function __construct(
        private SalaryHistoryFactory $salaryHistoryFactory,
        private ISalaryHistoryRepository $salaryHistoryRepository
    ) {}

    public function canStoreSalaryHistory(int $userId, int $year): bool
    {
        return !$this->salaryHistoryRepository->existsForUserInYear($userId, $year);
    }

    public function storeSalaryHistory(SalaryHistoryDTO $salaryHistoryDTO): SalaryHistory
    {
        $currentYear = Carbon::parse($salaryHistoryDTO->onDate)->format('Y');

        $canStoreSalaryHistory = $this->canStoreSalaryHistory(
            $salaryHistoryDTO->userId, 
            $currentYear
        );

        if (!$canStoreSalaryHistory) {
            throw new DomainException('User already has a record for this year.');
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
            throw new DatabaseException('Failed to fetch salary histories: ' . $e->getMessage());
        }
    }

    
    public function updateSalaryHistory(SalaryHistoryDTO $salaryHistoryDTO): void
    {
        $salaryHistory = $this->salaryHistoryRepository->findSalaryHistoryById($salaryHistoryDTO->id);

        if($salaryHistoryDTO->onDate) {
            $salaryHistory->setDate(new Date($salaryHistoryDTO->onDate));
        }

        if($salaryHistoryDTO->salary) {
            $salaryHistory->setSalary(new Salary($salaryHistoryDTO->salary));
        }

        $this->salaryHistoryRepository->updateSalaryHistory($salaryHistory);
    }
}