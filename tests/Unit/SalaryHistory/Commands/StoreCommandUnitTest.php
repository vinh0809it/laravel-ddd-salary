<?php

namespace Tests\Unit\SalaryHistory\Commands;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Application\Mappers\SalaryHistoryMapper;
use Src\SalaryHistory\Application\UseCases\CommandHandlers\StoreSalaryHistoryHandler;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Exceptions\UserHasSalaryRecordInYearException;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\Shared\Domain\Exceptions\EntityNotFoundException;
use Src\Shared\Domain\ValueObjects\Date;

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
            onDate: Date::fromString($this->faker->date()),
            salary: Salary::fromValue($this->faker->randomFloat(2, 0, 10000000)),
            currency: Currency::fromString($this->faker->randomElement(['USD', 'VND', 'JPY'])),
            note: $this->faker->sentence(),
        );
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_storeCommandHandler_handle_successful(): void
    {
        // Arrange
        $salaryHistory = SalaryHistoryMapper::fromDTO($this->dto);

        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($this->dto->userId, $this->dto->onDate)
            ->andReturn(true);

        $this->service->shouldReceive('storeSalaryHistory')
            ->with($this->dto)
            ->andReturn($salaryHistory);

        $storeCommand = new StoreSalaryHistoryCommand($this->dto);
        $storeHandler = new StoreSalaryHistoryHandler($this->service, $this->userDomainService);

        // // Act
        $result = $storeHandler->handle($storeCommand);

        // // Assert
        $this->assertIsArray($result);
    }

    public function test_storeCommandHandler_user_not_exists(): void
    {
        // Arrange
        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(false);

        // Assertion
        $this->expectException(EntityNotFoundException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->dto);
        $storeHandler = new StoreSalaryHistoryHandler($this->service, $this->userDomainService);

        $storeHandler->handle($storeCommand);
    }

    public function test_storeCommandHandler_can_not_store_salary_history(): void
    {
        // Arrange
        $this->userDomainService->shouldReceive('userExists')
            ->with($this->dto->userId)
            ->andReturn(true);

        $this->service->shouldReceive('canStoreSalaryHistory')
            ->with($this->dto->userId, $this->dto->onDate)
            ->andReturn(false);

        // Assertion
        $this->expectException(UserHasSalaryRecordInYearException::class);

        // Act
        $storeCommand = new StoreSalaryHistoryCommand($this->dto);
        $storeHandler = new StoreSalaryHistoryHandler($this->service, $this->userDomainService);

        $storeHandler->handle($storeCommand);
    }
}
