<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\Shared\Application\ICommand;

class StoreSalaryHistoryCommand implements ICommand
{
    public function __construct(
        public StoreSalaryHistoryDTO $dto
    )
    {}
}