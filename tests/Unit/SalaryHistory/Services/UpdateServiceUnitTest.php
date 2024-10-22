<?php

namespace Tests\Unit\SalaryHistory\Services;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\DatabaseException;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;

class UpdateServiceUnitTest extends TestCase
{
    use WithFaker;

    protected $salaryHistoryFactory;
    protected $salaryHistoryRepository;
    protected $userDomainService;
    protected $dto;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();

        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
        $this->userDomainService = Mockery::mock(IUserDomainService::class);

        $this->dto = UpdateSalaryHistoryDTO::create(
            id: $this->faker->uuid(), 
            onDate: Date::fromString($this->faker->date()),
            salary: Salary::fromValue($this->faker->randomFloat(2, 0, 10000000)),
            currency: Currency::fromString($this->faker->randomElement(['USD', 'VND', 'JPY'])),
            note: $this->faker->sentence()
        );
    }

    public function test_updateSalaryHistory_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($this->dto->id)
            ->andReturn($salaryHistory);

        $salaryHistory->shouldReceive('setDate')
            ->once()
            ->with(Mockery::type(Date::class));

        $salaryHistory->shouldReceive('setSalary')
            ->once()
            ->with(Mockery::type(Salary::class));

        $salaryHistory->shouldReceive('setCurrency')
            ->once()
            ->with(Mockery::type(Currency::class));

        $salaryHistory->shouldReceive('setNote')
            ->once()
            ->with(Mockery::type('string'));

        $this->salaryHistoryRepository->shouldReceive('updateSalaryHistory')
            ->once()
            ->with($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryRepository
        );

        // Act
        $salaryHistoryService->updateSalaryHistory($this->dto);

        // Assertions
        $this->assertTrue(true);
    }

    public function test_updateSalaryHistory_failed(): void
    {
        // Arrange
        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($this->dto->id)
            ->andThrow(new DatabaseException('Database error'));

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryRepository
        );

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database error');

        // Act
        $salaryHistoryService->updateSalaryHistory($this->dto);
    }
}
