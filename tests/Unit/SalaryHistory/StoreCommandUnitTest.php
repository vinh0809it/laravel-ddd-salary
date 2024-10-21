<?php

namespace Tests\Unit\SalaryHistory;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
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
    use WithFaker;

    private $service;
    private $userDomainService;
    private $dto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        
        $this->service = Mockery::mock(SalaryHistoryService::class);
        $this->userDomainService = Mockery::mock(IUserDomainService::class);

        $this->dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: $this->faker->uuid(),
            onDate: $this->faker->date(),
            salary: $this->faker->randomFloat(2, 0, 10000000),
            currency: $this->faker->randomElement(['USD', 'VND', 'JPY']),
            note: $this->faker->sentence()
        );
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_storeCommand_execute_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::Mock(SalaryHistory::class);

        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($this->dto->userId, Carbon::parse($this->dto->onDate)->format('Y'))
            ->andReturn(true);

        $this->service->shouldReceive('storeSalaryHistory')
            ->with($this->dto)
            ->andReturn($salaryHistory);

        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);

        // Act
        $result = $storeCommand->execute($this->dto);

        // Assert
        $this->assertSame($salaryHistory, $result);
    }

    public function test_storeCommand_user_not_exists(): void
    {
        // Arrange
        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(false);

        // Assertion
        $this->expectException(EntityNotFoundException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);
        $storeCommand->execute($this->dto);
    }

    public function test_storeCommand_can_not_store_salary_history(): void
    {
        // Arrange
        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($this->dto->userId, Carbon::parse($this->dto->onDate)->format('Y'))
            ->andReturn(false);

        // Assertion
        $this->expectException(UserHasSalaryRecordInYearException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->service, $this->userDomainService);
        $storeCommand->execute($this->dto);
    }
}
