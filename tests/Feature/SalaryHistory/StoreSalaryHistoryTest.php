<?php

namespace Tests\Feature\SalaryHistory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class StoreSalaryHistoryTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * @return void
     */
    public function test_store_salary_history_successful(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);
     
        $request = [
            'user_id' => (string)$user->id,
            'on_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(0, 10000000),
            'currency' => $this->faker->randomElement(['USD', 'VND', 'JPY']),
            'note' => $this->faker->sentence()
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
            'user_id' => (string)$user->id,
            'on_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(0, 10000000),
            'currency' => $this->faker->randomElement(['USD', 'VND', 'JPY']),
            'note' => $this->faker->sentence()
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
            'user_id' => (string)$user->id,
            'on_date' => 'invalid-date',
            'salary' => 'not-a-number',
            'currency' => 'not-a-currency',
            'note' => 'Testing'
        ];

        // Act
        $response = $this->postJson(route('salary_histories.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); 
        $response->assertJsonValidationErrors(['on_date', 'salary']); 
    }
}
