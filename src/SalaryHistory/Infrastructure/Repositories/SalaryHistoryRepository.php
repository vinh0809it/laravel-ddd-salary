<?php

namespace Src\SalaryHistory\Infrastructure\Repositories;

use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Infrastructure\BaseRepository;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Application\Mappers\SalaryHistoryMapper;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;

class SalaryHistoryRepository extends BaseRepository implements ISalaryHistoryRepository
{

    public function getModel(): string
    {
        return SalaryHistoryEloquentModel::class;
    }

    public function __construct() 
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

    public function getSalaryHistories(SalaryHistoryFilterDTO $filterDTO, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO
    {
        $query = $this->model->select(
            'id',
            'user_id',
            'on_date',
            'salary',
            'currency',
            'note'
        );

        if($filterDTO->userId) {
            $query->where('user_id', $filterDTO->userId);
        }
       
        if($filterDTO->dateRange) {
            $query->whereBetween('on_date', $filterDTO->dateRange->toArray());
        }

        $query->orderBy($pageMetaDTO->sort, $pageMetaDTO->sortDirection);

        $result = $query->paginate($pageMetaDTO->pageSize, ['*'], 'page', $pageMetaDTO->page);

        $transformedResults = $result->map(function ($eloquent) {
            return SalaryHistoryMapper::fromEloquent($eloquent);
        });

        return SalaryHistoryWithPageMetaDTO::fromPaginatedEloquent(
            data: $transformedResults,
            pagination: $this->getPagination($result),
            sorting: ['field' => $pageMetaDTO->sort, 'direction' => $pageMetaDTO->sortDirection]
        );
    }
    
    public function findSalaryHistoryById(string $id): ?SalaryHistory
    {
        $salaryHistory = $this->model->find($id);
        return $salaryHistory ? SalaryHistoryMapper::fromEloquent($salaryHistory) : null;
    }

    public function storeSalaryHistory(SalaryHistory $salaryHitory): SalaryHistory
    {
        $salaryHistoryEloquent = $this->model->create($salaryHitory->toArray());
        return SalaryHistoryMapper::fromEloquent($salaryHistoryEloquent);
    }

    public function updateSalaryHistory(SalaryHistory $salaryHistory): void
    {
        $this->model->find($salaryHistory->getId())->update($salaryHistory->toArray());
    }
}