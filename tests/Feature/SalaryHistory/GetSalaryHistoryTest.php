<?php

namespace Tests\Feature\SalaryHistory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class GetSalaryHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SalaryHistoryEloquentModel::factory()->count(2)->create([
            'user_id' => 2,
            'on_date' => '2024-08-01'
        ]);

        SalaryHistoryEloquentModel::factory()->count(5)->create([
            'user_id' => 1,
            'on_date' => '2024-08-01'
        ]);
    }
    
    public static function salaryHistoryProvider()
    {
        return [
            'with_all_filter' => [
                [
                    'user_id' => 1,
                    'from_date' => '2024-01-01',
                    'to_date' => '2024-10-01'
                ],
                5
            ],
            'with_only_user_id_provided' => [
                [
                    'user_id' => 2,
                    'from_date' => '2024-01-01',
                    'to_date' => '2024-10-01'
                ],
                2
            ],
            'with_only_date_range_provided' => [
                [
                    'from_date' => '2024-01-01',
                    'to_date' => '2024-10-01'
                ],
                7
            ],
            'with_no_filter_matched' => [
                [
                    'user_id' => 3,
                    'from_date' => '2024-01-01',
                    'to_date' => '2024-01-01'
                ],
                0
            ],
        ];
    }

    /**
     * @dataProvider salaryHistoryProvider
     */
    public function test_getSalaryHistory_successful_with_all_filter_provided($request, $expectCount): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);

        // Act
        $response = $this->getJson(route('salary_histories.get', $request));
        
        // Assertion
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($expectCount, 'data');
    }

}
