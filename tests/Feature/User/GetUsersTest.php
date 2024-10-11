<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Tests\TestCase;

class GetUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->artisan('migrate');
    }

    /**
     * A basic feature test example.
     */
    public function test_getUsers_successful(): void
    {
        // Arrange
        $userLoggedIn = UserEloquentModel::factory()->admin()->create();
        $this->actingAs($userLoggedIn);

        UserEloquentModel::factory()->create([
            'name' => 'AAAAAAAA',
            'email' => 'test_email1@example.com',
        ]);

        UserEloquentModel::factory()->create([
            'name' => 'BBBBB',
            'email' => 'test_email2@example.com',
        ]);

        $request = [
            'name' => 'AA',
            'email' => '',
        ];

        // Act
        $response = $this->getJson(route('users.get', $request));
        $users = $response->json('data');

        // Assertion
        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, $users);
        $this->assertEquals('AAAAAAAA', $users[0]['name']); 
    }
}
