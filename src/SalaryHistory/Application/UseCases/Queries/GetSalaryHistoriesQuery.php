<?php

namespace Src\SalaryHistory\Application\UseCases\Queries;

use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\Shared\Application\IQuery;

class GetSalaryHistoriesQuery implements IQuery
{
    public function __construct(
        public SalaryHistoryFilterDTO $filterDTO,
        public PageMetaDTO $pageMetaDTO
    )
    {}
}