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

    /**
     * @param string $userId
     * @param int $year
     * 
     * @return bool
     */
    public function existsForUserInYear(string $userId, int $year): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereYear('on_date', $year)
            ->exists();
    }

    /**
     * @param SalaryHistoryFilterDTO $filterDTO
     * @param PageMetaDTO $pageMetaDTO
     * 
     * @return SalaryHistoryWithPageMetaDTO
     */
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
    
    /**
     * @param string $id
     * 
     * @return SalaryHistory|null
     */
    public function findSalaryHistoryById(string $id): ?SalaryHistory
    {
        $salaryHistory = $this->model->find($id);
        return $salaryHistory ? SalaryHistoryMapper::fromEloquent($salaryHistory) : null;
    }

    /**
     * @param SalaryHistory $salaryHistory
     * 
     * @return SalaryHistory
     */
    public function storeSalaryHistory(SalaryHistory $salaryHistory): SalaryHistory
    {
        $params = SalaryHistoryMapper::toArray($salaryHistory);
        $salaryHistoryEloquent = $this->model->create($params);

        return SalaryHistoryMapper::fromEloquent($salaryHistoryEloquent);
    }

    /**
     * @param SalaryHistory $salaryHistory
     * 
     * @return void
     */
    public function updateSalaryHistory(SalaryHistory $salaryHistory): void
    {
        $params = SalaryHistoryMapper::toArray($salaryHistory);
        $this->model->find($salaryHistory->getId())->update($params);
    }
}