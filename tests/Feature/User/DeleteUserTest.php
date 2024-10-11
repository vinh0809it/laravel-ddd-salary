<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_delete_user_successfully(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);
     
        $user = UserEloquentModel::factory()->create([
            'name' => 'Delete User Test',
            'email' => 'test_email@example.com',
        ]);

        // Act
        $response = $this->deleteJson(route('users.delete', ['id' => $user->id]));
        
        // Assertions
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_delete_user_unauthorized(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->create();
        $this->actingAs($userLoggedIn);
    
        $user = UserEloquentModel::factory()->create([
            'name' => 'Update User Test',
            'email' => 'test_email@example.com',
        ]);

        // Act
        $response = $this->deleteJson(route('users.delete', ['id' => $user->id]));
        
        // Assertions
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_delete_user_not_exists(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);

        // Act
        $response = $this->deleteJson(route('users.delete', ['id' => 9999]));

        // Assertions
         $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
