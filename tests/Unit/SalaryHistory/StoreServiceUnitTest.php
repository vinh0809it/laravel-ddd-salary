<?php

namespace Tests\Unit\SalaryHistory;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Domain\Exceptions\DatabaseException;

class StoreServiceUnitTest extends TestCase
{
    private $factory;
    private $repository;
    private $dto;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = Mockery::mock(SalaryHistoryFactory::class);
        $this->repository = Mockery::mock(ISalaryHistoryRepository::class);
    
        $this->dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            currency: 'VND',
            note: 'Testing'
        );
    }

    public function test_storeSalaryHistory_service_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        $this->factory->shouldReceive('fromDTO')
            ->with($this->dto)
            ->andReturn($salaryHistory);

        $this->repository->shouldReceive('storeSalaryHistory')
            ->with($salaryHistory)
            ->andReturn($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService(
            $this->factory, 
            $this->repository
        );

        // Act
        $result = $salaryHistoryService->storeSalaryHistory($this->dto);

        // Assertions
        $this->assertSame($salaryHistory, $result);
    }

    public function test_storeSalaryHistory_database_exception(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        // Expectations
        $this->factory->shouldReceive('fromDTO')
            ->with($this->dto)
            ->andReturn($salaryHistory);

        $this->repository->shouldReceive('storeSalaryHistory')
            ->with($salaryHistory)
            ->andThrow(new DatabaseException('Database Error.'));

        $salaryHistoryService = new SalaryHistoryService(
            $this->factory, 
            $this->repository
        );

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database Error');

        // Act
        $salaryHistoryService->storeSalaryHistory($this->dto);
    }
}
