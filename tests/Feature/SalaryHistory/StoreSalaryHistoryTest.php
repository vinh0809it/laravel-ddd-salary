<?php

namespace Tests\Feature\SalaryHistory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class StoreSalaryHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_store_salary_history_successfully(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);
     
        $request = [
            'user_id' => (string)$user->id,
            'on_date' => '2024-10-06',
            'salary' => '5000000',
            'note' => 'Testing'
        ];

        // Act
        $response = $this->postJson(route('salary_histories.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('salary_histories', $request);

        $response->assertJsonStructure([
            'data' => [[
                'id',
                'user_id',
                'on_date',
                'salary',
                'note',
            ]]
        ]);
    }

    public function test_store_salary_history_unauthorized(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->create();
        $this->actingAs($user);

        $request = [
            'user_id' => '1',
            'on_date' => '2024-10-06',
            'salary' => 5000000,
            'note' => 'Testing'
        ];

        // Act
        $response = $this->postJson(route('salary_histories.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_store_salary_history_invalid_format(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);

        $request = [
            'user_id' => '1',
            'on_date' => 'invalid-date',
            'salary' => 'not-a-number',
            'note' => 'Testing'
        ];

        // Act
        $response = $this->postJson(route('salary_histories.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); 
        $response->assertJsonValidationErrors(['on_date', 'salary']); 
    }
}
