<?php

namespace Src\SalaryHistory\Infrastructure\Repositories;

use Src\Common\Application\DTOs\PageMetaDTO;
use Src\Common\Infrastructure\BaseRepository;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
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

    public function getSalaryHistories(SalaryHistoryFilterDTO $filter, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO
    {
        $query = $this->model->query();

        if($filter->userId) {
            $query->where('user_id', $filter->userId);
        }
       
        if($filter->dateRange) {
            $query->whereBetween('on_date', $filter->dateRange->toArray());
        }

        $query->orderBy($pageMetaDTO->sort, $pageMetaDTO->sortDirection);

        $result = $query->paginate($pageMetaDTO->pageSize, ['*'], 'page', $pageMetaDTO->page);

        $transformedResults = $result->map(function ($eloquent) {
            return $this->salaryHistoryFactory->fromEloquent($eloquent);
        });

        return SalaryHistoryWithPageMetaDTO::fromPaginatedEloquent(
            data: $transformedResults,
            pagination: $this->getPagination($result),
            sorting: ['field' => $pageMetaDTO->sort, 'direction' => $pageMetaDTO->sortDirection]
        );
    }
    
    public function findSalaryHistoryById(string $id): SalaryHistory
    {
        $salaryHistory = $this->model->find($id);
        return $this->salaryHistoryFactory->fromEloquent($salaryHistory);
    }

    public function storeSalaryHistory(SalaryHistory $salaryHitory): SalaryHistory
    {
        $salaryHistoryEloquent = $this->model->create($salaryHitory->toArray());
        return $this->salaryHistoryFactory->fromEloquent($salaryHistoryEloquent);
    }

    public function updateSalaryHistory(SalaryHistory $salaryHistory): void
    {
        $this->model->find($salaryHistory->getId())->update($salaryHistory->toArray());
    }
}