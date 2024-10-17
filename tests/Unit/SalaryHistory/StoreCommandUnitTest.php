<?php

namespace Tests\Unit\SalaryHistory;

use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;

class StoreCommandUnitTest extends TestCase
{
    private $service;
    private $userDomainService;
    private $storeCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(SalaryHistoryService::class);
        $this->userDomainService = Mockery::mock(IUserDomainService::class);

    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_storeCommand_execute_successful(): void
    {
        // Arrange
        $dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            currency: 'VND',
            note: 'Testing'
        );

        $salaryHistory = Mockery::Mock(SalaryHistory::class);

        $this->userDomainService->shouldReceive('userExists')
            ->with($dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($dto->userId, Carbon::parse($dto->onDate)->format('Y'))
            ->andReturn(true);

        $this->service->shouldReceive('storeSalaryHistory')
            ->with($dto)
            ->andReturn($salaryHistory);

        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);

        // Act
        $result = $storeCommand->execute($dto);

        // Assert
        $this->assertSame($salaryHistory, $result);
    }

    public function test_storeCommand_user_not_exists(): void
    {
        // Arrange
        $dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            currency: 'VND',
            note: 'Testing'
        );

        $this->userDomainService->shouldReceive('userExists')
            ->with($dto->userId)
            ->andReturn(false);

        // Assertion
        $this->expectException(EntityNotFoundException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);
        $storeCommand->execute($dto);
    }

    public function test_storeCommand_can_not_store_salary_history(): void
    {
        // Arrange
        $dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            currency: 'VND',
            note: 'Testing'
        );

        $this->userDomainService->shouldReceive('userExists')
            ->with($dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($dto->userId, Carbon::parse($dto->onDate)->format('Y'))
            ->andReturn(false);

        // Assertion
        $this->expectException(UserHasSalaryRecordInYearException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);
        $storeCommand->execute($dto);
    }
}
