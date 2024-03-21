<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    private User $user;

    /**
     * Function to set up the test
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Migrate and seed your database
        // Artisan::call('migrate');
        // Artisan::call('db:seed');

        // Create a test user with Sanctum tokens
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Function to test index
     *
     * @return void
     */
    public function test_index_a_collection_of_users()
    {
        $response = $this->json('GET', '/api/v1/users');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email'
                    ],
                ],
            ]);
    }

    /**
     * Function to test index with paginate
     *
     * @return void
     */
    public function test_index_a_collection_of_users_paginate()
    {
        $response = $this->json('GET', '/api/v1/users?paginate=1');

        // Assert
        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    /**
     * Function to test show by id
     *
     * @return void
     */
    public function test_show_user_by_id()
    {
        $response = $this->json('GET', '/api/v1/users/' . $this->user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email'
                ],
            ]);
    }

    /**
     * Function to test error for show by id
     *
     * @return void
     */
    public function test_error_show_user_by_id()
    {
        $response = $this->json('GET', '/api/v1/users/1500');

        $response->assertStatus(404)
            ->assertJsonStructure(['error', 'code']);
    }

    /**
     * Function to test store
     *
     * @return void
     */
    public function test_store_user()
    {
        $response = $this->json('POST', '/api/v1/users', [
            'name' => 'User',
            'email' => 'test@test',
            'image_path' => 'image',
            'password' => 'test',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email'
                ],
            ]);
        $data = $response->json();
        User::where('id', $data['data']['id'])->delete();
    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_user()
    {
        $response = $this->json('POST', '/api/v1/users', [
            'email' => 'test@test',
            'image_path' => 'image',
            'password' => 'test',
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    /**
     * Function to test update
     *
     * @return void
     */
    public function test_update_user()
    {
        $response = $this->json('PUT', '/api/v1/users/' . $this->user->id, [
            'name' => 'User Update',
            'email' => "test@test".strtotime("now").".com",
            'image_path' => 'image',
            'password' => 'test',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email'
                ],
            ]);
    }

    /**
     * Function to test error for update
     *
     * @return void
     */
    public function test_error_update_user()
    {
        $response = $this->json('PUT', '/api/v1/users/1500', [
            'name' => 'User Update',
            'email' => 'test@test',
            'image_path' => 'image',
            'password' => 'test',
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    /**
     * Test delete
     * Tener presente que una vez se corra la prueba, la segunda vez no encontrará el registro
     *
     * @return void
     */
    /**
     * Function to test delete
     *
     * @return void
     */
    public function test_delete_user()
    {
        $response = $this->json('DELETE', '/api/v1/users/' . $this->user->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    /**
     * Function to test error for delete
     *
     * @return void
     */
    public function test_error_delete_user()
    {
        $response = $this->json('DELETE', '/api/v1/users/1500');

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->user->delete();
    }
}
