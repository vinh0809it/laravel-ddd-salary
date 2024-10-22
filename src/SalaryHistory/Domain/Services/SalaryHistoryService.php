<?php

namespace Src\SalaryHistory\Domain\Services;

use Carbon\Carbon;
use Throwable;
use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Application\Mappers\SalaryHistoryMapper;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\Shared\Domain\ValueObjects\Date;

class SalaryHistoryService
{
    public function __construct(
        private ISalaryHistoryRepository $salaryHistoryRepository,
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
    public function canStoreSalaryHistory(string $userId, Date $onDate): bool
    {
        return !$this->salaryHistoryRepository->existsForUserInYear($userId, $onDate->format('Y'));
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

        $salaryHistory = SalaryHistoryFactory::create(
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
        $salaryHistory = SalaryHistoryMapper::fromDTO($dto);
       
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
        $salaryHistory = $this->getSalaryHistoryById($dto->id);

        $mappings = [
            'onDate' => 'setDate',
            'salary' => 'setSalary',
            'currency' => 'setCurrency',
            'note' => 'setNote',
        ];

        foreach ($mappings as $property => $method) {
            $value = $dto->$property;

            if($value !== null) {
                $salaryHistory->$method($value);
            }
        }

        try {
            $this->salaryHistoryRepository->updateSalaryHistory($salaryHistory);
        } catch (Throwable $e) {
            throw new DatabaseException('Failed to update salary history: ' . $e->getMessage());
        }
    }

    public function getSalaryHistoryById(string $id): SalaryHistory
    {
        try {
            $salaryHistory = $this->salaryHistoryRepository->findSalaryHistoryById($id);
        } catch(Throwable $e) {
            throw new DatabaseException('Failed to get the salary history: ' . $e->getMessage());
        }

        if(!$salaryHistory) {
            throw new EntityNotFoundException('The salary history is not existed.');
        }

        return $salaryHistory;
    }
    
}