<?php

namespace Src\SalaryHistory\Application\UseCases\Queries;

use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class GetSalaryHistoriesQuery
{

    public function __construct(private SalaryHistoryService $salaryHistoryService)
    {}

    /**
     * @param SalaryHistoryFilterDTO $filter
     * @param PageMetaDTO $pageMetaDTO
     * 
     * @return SalaryHistoryWithPageMetaDTO
     */
    public function handle(SalaryHistoryFilterDTO $filterDTO, PageMetaDTO $pageMetaDTO): SalaryHistoryWithPageMetaDTO
    {
        return $this->salaryHistoryService->getSalaryHistories($filterDTO, $pageMetaDTO);
    }
}