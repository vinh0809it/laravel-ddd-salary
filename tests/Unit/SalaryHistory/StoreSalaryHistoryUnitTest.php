<?php

namespace Tests\Unit\SalaryHistory;

use Carbon\Carbon;
use DomainException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;

class StoreSalaryHistoryUnitTest extends TestCase
{

    private $salaryHistoryFactory;
    private $salaryHistoryRepository;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock dependencies
        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
    }

    /**
     * @return void
     */
    public function test_canStoreSalaryHistory_when_user_has_no_record_in_year(): void
    {
        // Arrange
        $userId = 1;
        $year = 2024;

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        $this->salaryHistoryRepository->shouldReceive('existsForUserInYear')
            ->once()
            ->with($userId, $year)
            ->andReturn(false);

        // Act
        $result = $salaryHistoryService->canStoreSalaryHistory($userId, $year);

        // Assertions
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_canStoreSalaryHistory_when_user_has_record_in_year(): void
    {
        // Arrange
        $userId = 1;
        $year = 2024;

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        $this->salaryHistoryRepository->shouldReceive('existsForUserInYear')
            ->once()
            ->with($userId, $year)
            ->andReturn(true);

        // Act
        $result = $salaryHistoryService->canStoreSalaryHistory($userId, $year);

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function test_storeSalaryHistory_failed_when_user_has_record_in_year(): void
    {
        // Arrange
        $salaryHistoryDTO = new SalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            note: 'Testing'
        );

        $currentYear = Carbon::parse($salaryHistoryDTO->onDate)->format('Y');

        $this->salaryHistoryRepository->shouldReceive('existsForUserInYear')
            ->once()
            ->with($salaryHistoryDTO->userId, $currentYear)
            ->andReturn(true);

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Assertions
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('User already has a record for this year.');

        // Act
        $salaryHistoryService->storeSalaryHistory($salaryHistoryDTO);
    }

    /**
     * @return void
     */
    public function test_storeSalaryHistory_successful_when_user_has_no_record_in_year(): void
    {
        // Arrange
        $salaryHistory = new SalaryHistory(
            id: 1, 
            userId: 1,
            onDate: new Date('2024-10-06'),
            salary: new Salary(5000000),
            note: 'Testing'
        );

        $salaryHistoryDTO = new SalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            note: 'Testing'
        );

        $currentYear = Carbon::parse($salaryHistoryDTO->onDate)->format('Y');

        $this->salaryHistoryRepository->shouldReceive('existsForUserInYear')
            ->once()
            ->with($salaryHistoryDTO->userId, $currentYear)
            ->andReturn(false);

        $this->salaryHistoryFactory->shouldReceive('create')
            ->once()
            ->with(
                null,
                1,
                '2024-10-06',
                5000000,
                'Testing'
            )
            ->andReturn($salaryHistory);

        $this->salaryHistoryRepository->shouldReceive('storeSalaryHistory')
            ->once()
            ->with($salaryHistory)
            ->andReturn($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

        // Act
        $result = $salaryHistoryService->storeSalaryHistory($salaryHistoryDTO);

        // Assertions
        $this->assertInstanceOf(SalaryHistory::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals(1, $result->userId);
        $this->assertEquals('2024-10-06', $result->onDate->format());
        $this->assertEquals(5000000, $result->salary->getAmount());
        $this->assertEquals('Testing', $result->note);
    }
}
