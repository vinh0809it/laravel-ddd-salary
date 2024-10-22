<?php
namespace Src\SalaryHistory\Application\Mappers;

use Illuminate\Support\Collection;
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

    /**
     * @param mixed $entities
     * 
     * @return array
     */
    public static function toResponse(mixed $entities): array
    {
        $collection = $entities instanceof Collection ? $entities : collect([$entities]);
       
        return $collection->map(function (SalaryHistory $entity) {
            return [
                'id' => $entity->getId(),
                'user_id' => $entity->getUserId(),
                'on_date' => $entity->getOnDate()->format(),
                'salary' => $entity->getSalary()->toString(),
                'currency' => $entity->getCurrency()->toString(),
                'note' => $entity->getNote(),
            ];
        })->toArray();
    }

    /**
     * @param SalaryHistory $salaryHistory
     * 
     * @return array
     */
    public static function toArray(SalaryHistory $salaryHistory): array
    {
        return [
            'id' => $salaryHistory->getId(),
            'user_id' => $salaryHistory->getUserId(),
            'on_date' => $salaryHistory->getOnDate()->format(),
            'salary' => $salaryHistory->getSalary()->getAmount(),
            'currency' => $salaryHistory->getCurrency()->toString(),
            'note' => $salaryHistory->getNote()
        ];
    }
}