<?php

namespace Tests\Unit\SalaryHistory;

use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\StoreSalaryHistoryDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;

class StoreSalaryHistoryUnitTest extends TestCase
{
    private $salaryHistoryFactory;
    private $salaryHistoryRepository;
    private $userDomainService;

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

        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
        $this->userDomainService = Mockery::mock(IUserDomainService::class);
    }

    /**
     * @return void
     */
    public function test_canStoreSalaryHistory_when_user_has_no_record_in_year(): void
    {
        // Arrange
        $userId = 1;
        $year = 2024;

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryFactory, 
            $this->salaryHistoryRepository,
            $this->userDomainService
        );

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

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryFactory, 
            $this->salaryHistoryRepository,
            $this->userDomainService
        );

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
    public function test_storeSalaryHistory_successful_when_user_has_no_record_in_year(): void
    {
        // Arrange
        $salaryHistory = new SalaryHistory(
            id: 1, 
            userId: 1,
            onDate: Date::fromString('2024-10-06'),
            salary: Salary::fromValue(5000000),
            currency: Currency::fromString('VND'),
            note: 'Testing'
        );

        $dto = new StoreSalaryHistoryDTO(
            id: null,
            userId: 1,
            onDate: '2024-10-06',
            salary: 5000000,
            currency: 'VND',
            note: 'Testing'
        );

        $this->userDomainService->shouldReceive('userExists')
            ->once()
            ->with($dto->userId)
            ->andReturn(true);

        $this->salaryHistoryFactory->shouldReceive('fromDTO')
            ->once()
            ->with($dto)
            ->andReturn($salaryHistory);

        $this->salaryHistoryRepository->shouldReceive('storeSalaryHistory')
            ->once()
            ->with($salaryHistory)
            ->andReturn($salaryHistory);

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryFactory, 
            $this->salaryHistoryRepository,
            $this->userDomainService
        );

        // Act
        $result = $salaryHistoryService->storeSalaryHistory($dto);

        // Assertions
        $this->assertInstanceOf(SalaryHistory::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals(1, $result->getUserId());
        $this->assertEquals('2024-10-06', $result->getOnDate()->format());
        $this->assertEquals(5000000, $result->getSalary()->getAmount());
        $this->assertEquals('Testing', $result->getNote());
    }
}
