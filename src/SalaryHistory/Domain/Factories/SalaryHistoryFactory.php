<?php
namespace Src\SalaryHistory\Domain\Factories;

use Src\Shared\Domain\Exceptions\FactoryException;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
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
    public function create(?string $id, string $userId, string $onDate, float $salary, string $currency, ?string $note = null): SalaryHistory
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
     * @param StoreSalaryHistoryDTO $dto
     * 
     * @return SalaryHistory
     */
    public function fromDTO(StoreSalaryHistoryDTO $dto): SalaryHistory
    {
        return $this->create(
            id: $dto->id,
            userId: $dto->userId,
            onDate: $dto->onDate,
            salary: $dto->salary,
            currency: $dto->currency,
            note: $dto->note
        );
    }

    /**
     * @param SalaryHistoryEloquentModel $salaryHistoryEloquent
     * 
     * @return SalaryHistory
     */
    public function fromEloquent(SalaryHistoryEloquentModel $eloquent): SalaryHistory
    {
        return $this->create(
            id: $eloquent->id,
            userId: $eloquent->user_id,
            onDate: $eloquent->on_date,
            salary: $eloquent->salary,
            currency: $eloquent->currency,
            note: $eloquent->note
        );
    }
}