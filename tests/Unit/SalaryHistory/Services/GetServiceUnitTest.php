<?php

namespace Tests\Unit\SalaryHistory\Services;

use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Presentation\Requests\GetSalaryHistoryRequest;

class GetServiceUnitTest extends TestCase
{
    private $salaryHistoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salaryHistoryRepository = Mockery::mock(ISalaryHistoryRepository::class);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public static function salaryHistoryProvider()
    {
        return [
            'no parameters' => [
                new GetSalaryHistoryRequest(),
                new Collection([
                    new SalaryHistory(1, 1, Date::fromString('2024-10-01'), Salary::fromValue(5000000), Currency::fromString('VND'), ''),
                    new SalaryHistory(2, 2, Date::fromString('2024-10-02'), Salary::fromValue(6000000), Currency::fromString('VND'), ''),
                ]),
            ],
            'with from_date and to_date' => [
                new GetSalaryHistoryRequest(['from_date' => '2024-10-01', 'to_date' => '2024-11-01']),
                new Collection([
                    new SalaryHistory(1, 1, Date::fromString('2024-10-01'), Salary::fromValue(5000000), Currency::fromString('VND'), ''),
                    new SalaryHistory(2, 2, Date::fromString('2024-10-02'), Salary::fromValue(6000000), Currency::fromString('VND'), ''),
                ]),
            ],
        ];
    }

    /**
     * @dataProvider salaryHistoryProvider
     */
    public function test_getSalaryHistories(GetSalaryHistoryRequest $request, Collection $expectedSalaryHistories): void
    {
        // Arrange
        $filterDTO = SalaryHistoryFilterDTO::fromRequest($request);
        
        $pageMetaDTO = PageMetaDTO::fromRequest($request);

        $resultWithPageMetaDTO = SalaryHistoryWithPageMetaDTO::fromPaginatedEloquent(
            data: $expectedSalaryHistories,
            pagination: [],
            sorting: ['field' => $pageMetaDTO->sort, 'direction' => $pageMetaDTO->sortDirection]
        );

        $this->salaryHistoryRepository->shouldReceive('getSalaryHistories')
            ->with($filterDTO, $pageMetaDTO)
            ->andReturn($resultWithPageMetaDTO);

        $salaryHistoryService = new SalaryHistoryService(
            $this->salaryHistoryRepository
        );

        // Act
        $result = $salaryHistoryService->getSalaryHistories($filterDTO, $pageMetaDTO);

        // Assertion
        $this->assertInstanceOf(SalaryHistoryWithPageMetaDTO::class, $result);
        $this->assertInstanceOf(Collection::class, $result->salaryHistories);
        $this->assertCount(2, $result->salaryHistories);
        $this->assertEquals($expectedSalaryHistories, $result->salaryHistories);
        $this->assertEquals([], $result->pagination);
        $this->assertEquals(['field' => $pageMetaDTO->sort, 'direction' => $pageMetaDTO->sortDirection], $result->sorting);
    }
}
