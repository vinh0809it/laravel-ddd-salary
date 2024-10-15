<?php
namespace Src\SalaryHistory\Domain\Factories;

use Src\Common\Domain\Exceptions\FactoryException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Throwable;

class SalaryHistoryFactory
{
    /**
     * @param string|null $id
     * @param string $userId
     * @param string $onDate
     * @param float $salary
     * @param string|null $note
     * 
     * @return SalaryHistory
     */
    public function create(?string $id, string $userId, string $onDate, float $salary, string $currency, ?string $note): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $id,
                userId: $userId,
                onDate: Date::fromString($onDate),
                salary: Salary::fromValue($salary),
                currency: Currency::fromString($currency),
                note: $note
            );

        } catch(Throwable $e) {
            
            throw new FactoryException('Error creating SalaryHistory: ' . $e->getMessage());
        }
    }

    /**
     * @param SalaryHistoryDTO $dto
     * 
     * @return SalaryHistory
     */
    public function createFromDTO(SalaryHistoryDTO $dto): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $dto->id,
                userId: $dto->userId,
                onDate: Date::fromString($dto->onDate),
                salary: Salary::fromValue($dto->salary),
                currency: Currency::fromString($dto->currency),
                note: $dto->note
            );

        } catch(Throwable $e) {
            
            throw new FactoryException('Error creating SalaryHistory from DTO: ' . $e->getMessage());
        }
    }

    /**
     * @param SalaryHistoryEloquentModel $salaryHistoryEloquent
     * 
     * @return SalaryHistory
     */
    public function fromEloquent(SalaryHistoryEloquentModel $salaryHistoryEloquent): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $salaryHistoryEloquent->id,
                userId: $salaryHistoryEloquent->user_id,
                onDate: Date::fromString($salaryHistoryEloquent->on_date),
                salary: Salary::fromValue($salaryHistoryEloquent->salary),
                currency: Currency::fromString($salaryHistoryEloquent->currency),
                note: $salaryHistoryEloquent->note
            );

        } catch(Throwable $e) {
            throw new FactoryException('Error creating SalaryHistory from Eloquent: ' . $e->getMessage());
        }
    }
}