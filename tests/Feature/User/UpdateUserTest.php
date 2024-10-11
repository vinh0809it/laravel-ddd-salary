<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_update_user_successful(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);
     
        $user = UserEloquentModel::factory()->create([
            'name' => 'Update User Test',
            'email' => 'test_email@example.com',
        ]);

        $request = [
            'name' => 'Updated User',
        ];

        // Act
        $response = $this->putJson(route('users.update', ['id' => $user->id]), $request);
        
        // Assertions
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_update_user_unauthorized(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->create();
        $this->actingAs($userLoggedIn);
    
        $user = UserEloquentModel::factory()->create([
            'name' => 'Update User Test',
            'email' => 'test_email@example.com',
        ]);

        $request = [
            'name' => 'Updated User',
        ];

        // Act
        $response = $this->putJson(route('users.update', ['id' => $user->id]), $request);
        
        // Assertions
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_store_user_invalid_format(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);
    
        $user = UserEloquentModel::factory()->create([
            'name' => 'Update User Test',
            'email' => 'test_email@example.com',
        ]);

        $request = [
            'name' => 'Updated User',
            'email' => 'invalid_email',
        ];

        // Act
        $response = $this->putJson(route('users.update', ['id' => $user->id]), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); 
        $response->assertJsonValidationErrors(['email']); 
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
