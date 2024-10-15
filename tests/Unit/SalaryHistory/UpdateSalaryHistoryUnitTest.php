<?php

namespace Tests\Unit\SalaryHistory;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;

class UpdateSalaryHistoryUnitTest extends TestCase
{
    protected $salaryHistoryFactory;
    protected $salaryHistoryRepository;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
    }

    public function test_updateSalaryHistory_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        $updateDTO = UpdateSalaryHistoryDTO::create(
            id: 1, 
            onDate: Date::fromString('2024-10-11'),
            salary: Salary::fromValue(10000000),
            currency: Currency::fromString('VND'),
            note: null
        );

        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($updateDTO->id)
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

        $this->salaryHistoryRepository->shouldReceive('updateSalaryHistory')
            ->once()
            ->with($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Act
        $salaryHistoryService->updateSalaryHistory($updateDTO);

        // Assertions
        $this->assertTrue(true);
    }

    public function test_updateUser_failed(): void
    {
        // Arrange
        $updateDTO = UpdateSalaryHistoryDTO::create(
            id: 1, 
            onDate: Date::fromString('2024-10-11'),
            salary: Salary::fromValue(10000000),
            currency: Currency::fromString('VND'),
            note: "Noted"
        );

        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($updateDTO->id)
            ->andThrow(new DatabaseException('Database error'));

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database error');

        // Act
        $salaryHistoryService->updateSalaryHistory($updateDTO);
    }
}
