<?php

namespace Src\SalaryHistory\Application\UseCases\CommandHandlers;

use Carbon\Carbon;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;

class StoreSalaryHistoryHandler
{
    public function __construct(
        private SalaryHistoryService $salaryHistoryService,
        private IUserDomainService $userDomainService
    )
    {}

    public function handle(StoreSalaryHistoryCommand $command): SalaryHistory
    {
        $dto = $command->dto;

        if(!$this->userDomainService->userExists($dto->userId)) {
            throw new EntityNotFoundException('The user is not existed.');
        }

        $currentYear = Carbon::parse($dto->onDate)->format('Y');

        if (!$this->salaryHistoryService->canStoreSalaryHistory($dto->userId, $currentYear)) {
            throw new UserHasSalaryRecordInYearException();
        }

        return $this->salaryHistoryService->storeSalaryHistory($dto);
    }
}