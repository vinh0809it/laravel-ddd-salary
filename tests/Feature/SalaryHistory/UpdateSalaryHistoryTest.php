<?php

namespace Tests\Feature\SalaryHistory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class UpdateSalaryHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_updateSalaryHistory_successful(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);

        $salaryHistory = SalaryHistoryEloquentModel::factory()->create();

        $request = [
            'on_date' => '2024-10-11',
            'salary' => 10000000,
        ];

        // Act
        $response = $this->putJson(route('salary_histories.update', $salaryHistory->id), $request);

        // Assertion
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('salary_histories', $request);
    }

    public function test_updateSalaryHistory_history_unauthorized(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->create();
        $this->actingAs($user);

        $salaryHistory = SalaryHistoryEloquentModel::factory()->create();

        $request = [];

        // Act
        $response = $this->putJson(route('salary_histories.update', ['id' => $salaryHistory->id]), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_updateSalaryHistory_invalid_format(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);

        $request = [
            'on_date' => 'invalid-date',
            'salary' => 'not-a-number',
        ];

        // Act
        $response = $this->putJson(route('salary_histories.update', ['id' => 1]), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); 
        $response->assertJsonValidationErrors(['on_date', 'salary']); 
    }
}
