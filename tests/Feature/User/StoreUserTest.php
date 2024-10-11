<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_store_user_successfully(): void
    {
        // Arrange
        $user = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($user);
     
        $request = [
            'name' => 'Admin User',
            'email' => 'vinh0809it@gmail.com',
            'is_admin' => false,
            'is_active' => true,
            'password' => '12345678'
        ];

        // Act
        $response = $this->postJson(route('users.store'), $request);

        // Assertions
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin User',
            'email' => (string)new Email('vinh0809it@gmail.com'),
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
            'name' => 'Admin User',
            'email' => 'vinh0809it@gmail.com',
            'is_admin' => false,
            'is_active' => true,
            'password' => '12345678'
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
            'name' => 'Admin User',
            'email' => 'invalid_email',
            'is_admin' => false,
            'is_active' => true,
            'password' => '12345678'
        ];

        // Act
        $response = $this->postJson(route('users.store'), $request);

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
