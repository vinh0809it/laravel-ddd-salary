<?php

namespace Tests\Unit\SalaryHistory;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Domain\Exceptions\DatabaseException;

class StoreServiceUnitTest extends TestCase
{
    use WithFaker;

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
        $this->setUpFaker();
        $this->repository = Mockery::mock(ISalaryHistoryRepository::class);
    
        $this->dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: $this->faker->uuid(),
            onDate: $this->faker->date(),
            salary: $this->faker->randomFloat(2, 0, 10000000),
            currency: $this->faker->randomElement(['USD', 'VND', 'JPY']),
            note: $this->faker->sentence()
        );
    }

    public function test_storeSalaryHistory_service_successful(): void
    {
        // Arrange
        $salaryHistory = Mockery::mock(SalaryHistory::class);

        $this->repository->shouldReceive('storeSalaryHistory')
            ->andReturn($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService(
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
        $this->repository->shouldReceive('storeSalaryHistory')
            ->andThrow(new DatabaseException('Database Error.'));

        $salaryHistoryService = new SalaryHistoryService(
            $this->repository
        );

        // Assert
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database Error');

        // Act
        $salaryHistoryService->storeSalaryHistory($this->dto);
    }
}
