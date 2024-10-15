<?php

namespace Tests\Unit\SalaryHistory;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Common\Domain\Exceptions\DatabaseException;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

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

        // Mock dependencies
        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
    }

    public function test_updateSalaryHistory_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        $salaryHistoryDTO = new SalaryHistoryDTO(
            id: 1, 
            userId: 1,
            onDate: '2024-10-11',
            salary: 10000000,
            note: "Noted"
        );

        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($salaryHistoryDTO->id)
            ->andReturn($salaryHistory);

        $salaryHistory->shouldReceive('setDate')
            ->once()
            ->with(Mockery::type(Date::class));

        $salaryHistory->shouldReceive('setSalary')
            ->once()
            ->with(Mockery::type(Salary::class));

        $this->salaryHistoryRepository->shouldReceive('updateSalaryHistory')
            ->once()
            ->with($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Act
        $salaryHistoryService->updateSalaryHistory($salaryHistoryDTO);

        // Assertions
        $this->assertTrue(true); // No exception means success
    }

    public function test_updateUser_failed(): void
    {
        // Arrange
        $salaryHistoryDTO = new SalaryHistoryDTO(
            id: 1, 
            userId: 1,
            onDate: '2024-10-11',
            salary: 10000000,
            note: "Noted"
        );

        $this->salaryHistoryRepository->shouldReceive('findSalaryHistoryById')
            ->once()
            ->with($salaryHistoryDTO->id)
            ->andThrow(new DatabaseException('Database error'));

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database error');

        // Act
        $salaryHistoryService->updateSalaryHistory($salaryHistoryDTO);
    }
}
