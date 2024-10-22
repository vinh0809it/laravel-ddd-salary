<?php

namespace Tests\Unit\SalaryHistory\Commands;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Application\UseCases\CommandHandlers\UpdateSalaryHistoryHandler;
use Src\SalaryHistory\Application\UseCases\Commands\UpdateSalaryHistoryCommand;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\Shared\Domain\ValueObjects\Date;

class UpdateCommandUnitTest extends TestCase
{
    use WithFaker;

    private $service;
    private $dto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        
        $this->service = Mockery::mock(SalaryHistoryService::class);

        $this->dto = UpdateSalaryHistoryDTO::create(
            id: $this->faker->uuid(), 
            onDate: Date::fromString($this->faker->date()),
            salary: Salary::fromValue($this->faker->randomFloat(2, 0, 10000000)),
            currency: Currency::fromString($this->faker->randomElement(['USD', 'VND', 'JPY'])),
            note: $this->faker->sentence()
        );
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_updateCommandHandler_handle_successful(): void
    {
        // Arrange
        $this->service->shouldReceive('updateSalaryHistory')
            ->with($this->dto)
            ->andReturnNull();

        $storeCommand = new UpdateSalaryHistoryCommand($this->dto);
        $storeHandler = new UpdateSalaryHistoryHandler($this->service);

        // // Act
        $storeHandler->handle($storeCommand);

        // // Assert
        $this->assertTrue(true);
    }
}
