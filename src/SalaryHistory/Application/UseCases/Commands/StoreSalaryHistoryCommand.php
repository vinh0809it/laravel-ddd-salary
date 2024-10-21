<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;

class StoreSalaryHistoryCommand
{
    public function __construct(
        public StoreSalaryHistoryDTO $dto
    )
    {}
}