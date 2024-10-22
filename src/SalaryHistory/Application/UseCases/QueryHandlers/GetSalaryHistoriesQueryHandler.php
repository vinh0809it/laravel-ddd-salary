<?php

namespace Src\SalaryHistory\Application\UseCases\QueryHandlers;

use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Application\UseCases\Queries\GetSalaryHistoriesQuery;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Application\Exceptions\InvalidQueryProvided;
use Src\Shared\Domain\IQuery;
use Src\Shared\Domain\IQueryHandler;

class GetSalaryHistoriesQueryHandler implements IQueryHandler
{

    public function __construct(private SalaryHistoryService $salaryHistoryService)
    {}

    /**
     * @param SalaryHistoryFilterDTO $filter
     * @param PageMetaDTO $pageMetaDTO
     * 
     * @return SalaryHistoryWithPageMetaDTO
     */
    public function handle(IQuery $query): mixed
    {
        if (!$query instanceof GetSalaryHistoriesQuery) {
            throw new InvalidQueryProvided();
        }

        return $this->salaryHistoryService->getSalaryHistories($query->filterDTO, $query->pageMetaDTO);
    }
}