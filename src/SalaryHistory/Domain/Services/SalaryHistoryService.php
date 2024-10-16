<?php

namespace Src\SalaryHistory\Domain\Services;

use Carbon\Carbon;
use Throwable;
use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;

class SalaryHistoryService
{
    public function __construct(
        private SalaryHistoryFactory $salaryHistoryFactory,
        private ISalaryHistoryRepository $salaryHistoryRepository,
        private IUserDomainService $userDomainService
    ) {}

    /**
     * @param SalaryHistoryFilterDTO $filterDTO
     * @param PageMetaDTO $pageMetaDTO
     * 
     * @return SalaryHistoryWithPageMetaDTO
     */
    public function getSalaryHistories(SalaryHistoryFilterDTO $filterDTO, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO
    {
        try {
            return $this->salaryHistoryRepository->getSalaryHistories($filterDTO, $pageMetaDTO);
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
     * @param string $userId
     * 
     * @return SalaryHistory
     */
    public function storeSalaryHistoryForNewUser(string $userId): SalaryHistory
    {
        $toDay = Carbon::today()->format('Y-m-d');

        // These info should be retrieved by user role/position or default
        $salary = 5000000;
        $currency = 'VND';
        $note = 'Initial salary';

        $salaryHistory = $this->salaryHistoryFactory->create(
            null,
            $userId,
            $toDay,
            $salary,
            $currency,
            $note
        );

        try {
            return $this->salaryHistoryRepository->storeSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store salary history for new user: ' . $e->getMessage());
        }
    }

    /**
     * @param StoreSalaryHistoryDTO $dto
     * 
     * @return SalaryHistory
     */
    public function storeSalaryHistory(StoreSalaryHistoryDTO $dto): SalaryHistory
    {
        if(!$this->userDomainService->userExists($dto->userId)) {
            throw new EntityNotFoundException('The user is not existed.');
        }

        $currentYear = Carbon::parse($dto->onDate)->format('Y');

        $canStoreSalaryHistory = $this->canStoreSalaryHistory(
            $dto->userId, 
            $currentYear
        );

        if (!$canStoreSalaryHistory) {
            throw new UserHasSalaryRecordInYearException();
        }

        $salaryHistory = $this->salaryHistoryFactory->fromDTO($dto);

        try {
            return $this->salaryHistoryRepository->storeSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to store salary history: ' . $e->getMessage());
        }
    }
    
    /**
     * @param UpdateSalaryHistoryDTO $dto
     * 
     * @return void
     */
    public function updateSalaryHistory(UpdateSalaryHistoryDTO $dto): void
    {
        $salaryHistory = $this->salaryHistoryRepository->findSalaryHistoryById($dto->id);

        if(!$salaryHistory) {
            throw new EntityNotFoundException('The salary history is not existed.');
        }

        $mappings = [
            'onDate' => 'setDate',
            'salary' => 'setSalary',
            'currency' => 'setCurrency',
            'note' => 'setNote',
        ];

        foreach ($mappings as $property => $method) {
            $value = $dto->$property;

            if($value) {
                $salaryHistory->$method($value);
            }
        }

        try {
            $this->salaryHistoryRepository->updateSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to update salary history: ' . $e->getMessage());
        }
    }
}