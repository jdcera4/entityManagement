<?php

namespace Tests\Feature;

use App\Models\Programs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramsControllerTest extends TestCase
{
    //use RefreshDatabase;

    private User $user;
    private Programs $program;

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
        $this->program = Programs::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Function to test index
     *
     * @return void
     */
    public function test_index_a_collection_of_programs()
    {
        $response = $this->json('GET', '/api/v1/programs');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'start_date',
                        'end_date',
                        'user' => [
                            'id',
                            'name',
                            'email'
                        ]
                    ],
                ],
            ]);
    }

    /**
     * Function to test index with paginate
     *
     * @return void
     */
    public function test_index_a_collection_of_programs_paginate()
    {
        $response = $this->json('GET', '/api/v1/programs?paginate=1');

        // Assert
        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    /**
     * Function to test show by id
     *
     * @return void
     */
    public function test_show_program_by_id()
    {
        $response = $this->json('GET', '/api/v1/programs/' . $this->program->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'start_date',
                    'end_date',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ],
            ]);
    }

    /**
     * Function to test error for show by id
     *
     * @return void
     */
    public function test_error_show_program_by_id()
    {
        $response = $this->json('GET', '/api/v1/programs/1500');

        $response->assertStatus(404)
            ->assertJsonStructure(['error', 'code']);
    }

    /**
     * Function to test store
     *
     * @return void
     */
    public function test_store_program()
    {
        $response = $this->json('POST', '/api/v1/programs', [
            'title' => 'Test',
            'description' => 'Test',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-01',
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'start_date',
                    'end_date',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ],
            ]);
        $data = $response->json();
        Programs::where('id', $data['data']['id'])->delete();
    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_program()
    {
        $response = $this->json('POST', '/api/v1/programs', [
            'description' => 'Test',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-01',
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    /**
     * Function to test update
     *
     * @return void
     */
    public function test_update_program()
    {
        $response = $this->json('PUT', '/api/v1/programs/' . $this->program->id, [
            'title' => 'Update Test',
            'description' => 'Test',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-01',
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'start_date',
                    'end_date',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ],
            ]);
    }

    /**
     * Function to test error for update
     *
     * @return void
     */
    public function test_error_update_program()
    {
        $response = $this->json('PUT', '/api/v1/programs/1500', [
            'title' => 'Update Test',
            'description' => 'Test',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-01',
            'user_id' => $this->user->id
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
    public function test_delete_program()
    {
        $response = $this->json('DELETE', '/api/v1/programs/' . $this->program->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    /**
     * Function to test error for delete
     *
     * @return void
     */
    public function test_error_delete_program()
    {
        $response = $this->json('DELETE', '/api/v1/programs/1500');

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->program->delete();
        $this->user->delete();
    }
}
