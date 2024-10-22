<?php
namespace Src\SalaryHistory\Application\Mappers;

use Src\Shared\Domain\Exceptions\FactoryException;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Throwable;

class SalaryHistoryMapper
{
    /**
     * @param SalaryHistoryEloquentModel $eloquent
     * 
     * @return SalaryHistory
     */
    public static function fromEloquent(SalaryHistoryEloquentModel $eloquent): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $eloquent->id,
                userId: $eloquent->user_id,
                onDate: Date::fromString($eloquent->on_date),
                salary: Salary::fromValue($eloquent->salary),
                currency: Currency::fromString($eloquent->currency),
                note: $eloquent->note
            );

        } catch(Throwable $e) {
            
            throw new FactoryException('Error creating SalaryHistory by Mapper: ' . $e->getMessage());
        }
    }

    /**
     * @param StoreSalaryHistoryDTO $dto
     * 
     * @return SalaryHistory
     */
    public static function fromDTO(StoreSalaryHistoryDTO $dto): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $dto->id,
                userId: $dto->userId,
                onDate: $dto->onDate,
                salary: $dto->salary,
                currency: $dto->currency,
                note: $dto->note
            );

        } catch(Throwable $e) {
            
            throw new FactoryException('Error creating SalaryHistory by Mapper: ' . $e->getMessage());
        }
    }
}