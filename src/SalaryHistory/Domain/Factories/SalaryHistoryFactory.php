<?php
namespace Src\SalaryHistory\Domain\Factories;

use Src\Shared\Domain\Exceptions\FactoryException;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
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
    public static function create(?string $id, string $userId, string $onDate, float $salary, string $currency, ?string $note = null): SalaryHistory
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
            
            throw new FactoryException('Error creating SalaryHistory by Factory: ' . $e->getMessage());
        }
    }
}