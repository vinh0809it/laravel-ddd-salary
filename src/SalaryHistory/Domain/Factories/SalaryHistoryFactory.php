<?php
namespace Src\SalaryHistory\Domain\Factories;

use Src\Common\Domain\Exceptions\FactoryException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Domain\Model\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Model\ValueObjects\UserId;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Throwable;

class SalaryHistoryFactory
{
    /**
     * @param string|null $id
     * @param string $userId
     * @param string $onDate
     * @param int $salary
     * @param string|null $note
     * 
     * @return SalaryHistory
     */
    public function create(?string $id, string $userId, string $onDate, int $salary, ?string $note): SalaryHistory
    {
        try {
            return new SalaryHistory(
                id: $id,
                userId: new UserId($userId),
                onDate: new Date($onDate),
                salary: new Salary($salary),
                note: $note
            );

        } catch(Throwable $e) {
            throw new FactoryException();
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
                userId: new UserId($salaryHistoryEloquent->user_id),
                onDate: new Date($salaryHistoryEloquent->on_date),
                salary: new Salary($salaryHistoryEloquent->salary),
                note: $salaryHistoryEloquent->note
            );

        } catch(Throwable $e) {
            throw new FactoryException();
        }
    }
}