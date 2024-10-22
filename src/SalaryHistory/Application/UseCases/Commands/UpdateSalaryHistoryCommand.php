<?php

namespace Src\SalaryHistory\Application\UseCases\Commands;

use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\Shared\Application\ICommand;

class UpdateSalaryHistoryCommand implements ICommand
{
    public function __construct(
        public UpdateSalaryHistoryDTO $dto
    )
    {}
}