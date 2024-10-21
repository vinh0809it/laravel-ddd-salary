<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class StoreUserTest extends TestCase
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
    public function test_store_user_successfully(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);
     
        $name = $this->faker->name();
        $email = $this->faker->safeEmail();

        $request = [
            'name' => $name,
            'email' => $email,
            'is_admin' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'password' => $this->faker->password()
        ];

        // Act
        $response = $this->postJson(route('users.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertJsonStructure([
            'data' => [[
                'id',
                'name',
                'email',
                'is_admin'
            ]]
        ]);
    }

    public function test_store_user_unauthorized(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->create();
        $this->actingAs($user);

        $request = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'is_admin' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'password' => $this->faker->password()
        ];

        // Act
        $response = $this->postJson(route('users.store'), $request);
        
        // Assertions
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_store_user_invalid_format(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);

        $request = [
            'name' => $this->faker->name(),
            'email' => 'invalid_email',
        ];

        // Act
        $response = $this->postJson(route('users.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); 
        $response->assertJsonValidationErrors(['email']); 
    }
}
