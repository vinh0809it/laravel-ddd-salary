<?php

namespace Tests\Unit\SalaryHistory;

use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Common\Application\DTOs\PageMetaDTO;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Domain\Factories\SalaryHistoryFactory;
use Src\SalaryHistory\Domain\Model\SalaryHistory;
use Src\SalaryHistory\Domain\Repositories\ISalaryHistoryRepository;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\SalaryHistory\Presentation\Requests\GetSalaryHistoryRequest;

class GetSalaryHistoryUnitTest extends TestCase
{
    private $salaryHistoryFactory;
    private $salaryHistoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salaryHistoryFactory = Mockery::mock(SalaryHistoryFactory::class);
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
                    new SalaryHistory(1, 1, new Date('2024-10-01'), new Salary(5000000), ''),
                    new SalaryHistory(2, 2, new Date('2024-10-02'), new Salary(6000000), ''),
                ]),
            ],
            'with from_date and to_date' => [
                new GetSalaryHistoryRequest(['from_date' => '2024-10-01', 'to_date' => '2024-11-01']),
                new Collection([
                    new SalaryHistory(1, 1, new Date('2024-10-01'), new Salary(5000000), ''),
                    new SalaryHistory(2, 2, new Date('2024-10-02'), new Salary(6000000), ''),
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

        $salaryHistoryService = new SalaryHistoryService($this->salaryHistoryFactory, $this->salaryHistoryRepository);

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
