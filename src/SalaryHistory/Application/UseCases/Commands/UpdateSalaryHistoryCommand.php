<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;

class UpdateSalaryHistoryCommand
{
    public function __construct(
        public UpdateSalaryHistoryDTO $dto
    )
    {}
}