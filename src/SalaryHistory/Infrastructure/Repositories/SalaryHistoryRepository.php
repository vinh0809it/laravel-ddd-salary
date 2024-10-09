<?php

namespace Src\SalaryHistory\Infrastructure\Repositories;

use Src\Common\Infrastructure\BaseRepository;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;

class SalaryHistoryRepository extends BaseRepository implements ISalaryHistoryRepository
{

    public function getModel(): string
    {
        return SalaryHistoryEloquentModel::class;
    }

    public function __construct(private SalaryHistoryFactory $salaryHistoryFactory) 
    {
        parent::__construct();
    }

    public function existsForUserInYear(string $userId, int $year): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereYear('on_date', $year)
            ->exists();
    }

    public function storeSalaryHistory(SalaryHistory $salaryHitory): SalaryHistory
    {
        $salaryHistoryEloquent = $this->model->create($salaryHitory->toArray());
        return $this->salaryHistoryFactory->fromEloquent($salaryHistoryEloquent);
    }
}