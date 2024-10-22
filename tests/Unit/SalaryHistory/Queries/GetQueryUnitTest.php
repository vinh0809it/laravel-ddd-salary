<?php

namespace Tests\Unit\SalaryHistory\Commands;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryWithPageMetaDTO;
use Src\SalaryHistory\Application\UseCases\Queries\GetSalaryHistoriesQuery;
use Src\SalaryHistory\Application\UseCases\Queries\InvalidQuery;
use Src\SalaryHistory\Application\UseCases\QueryHandlers\GetSalaryHistoriesQueryHandler;
use Src\SalaryHistory\Domain\Services\SalaryHistoryService;
use Src\Shared\Application\DTOs\PageMetaDTO;
use Src\Shared\Application\Exceptions\InvalidQueryProvided;

class GetQueryUnitTest extends TestCase
{
    use WithFaker;

    private $service;
    private $userDomainService;
    private $pageMetaDTO;
    private $filterDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        
        $this->service = Mockery::mock(SalaryHistoryService::class);

        $this->pageMetaDTO = PageMetaDTO::fromRequest(new Request());
        $this->filterDTO = SalaryHistoryFilterDTO::fromRequest(new Request());
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_getQueryHandler_handle_successful(): void
    {
        // Arrange
        $salaryHistoryWithPageMetaDTO = Mockery::mock(SalaryHistoryWithPageMetaDTO::class);

        $this->service->shouldReceive('getSalaryHistories')
            ->with($this->filterDTO, $this->pageMetaDTO)
            ->andReturn($salaryHistoryWithPageMetaDTO);

        $getQuery = new GetSalaryHistoriesQuery($this->filterDTO, $this->pageMetaDTO);
        $getHandler = new GetSalaryHistoriesQueryHandler($this->service);

        // Act
        $result = $getHandler->handle($getQuery);

        // Assert
        $this->assertInstanceOf(SalaryHistoryWithPageMetaDTO::class, $result);
    }

    public function test_getQueryHandler_invalid_query(): void
    {
        // Arrange
        $invalidQuery = new InvalidQuery();
        $getHandler = new GetSalaryHistoriesQueryHandler($this->service);

        // Assertion
        $this->expectException(InvalidQueryProvided::class);

        // Act
        $getHandler->handle($invalidQuery);
    }
}
