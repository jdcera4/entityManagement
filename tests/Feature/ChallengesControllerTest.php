<?php

namespace Tests\Feature;

use App\Models\Challenges;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengesControllerTest extends TestCase
{
    //use RefreshDatabase;

    private User $user;

    private Challenges $challenge;

    /**
     * Function to set up the test
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create a test user with Sanctum tokens
        $this->user = User::factory()->create();
        $this->challenge = Challenges::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Function to test index
     *
     * @return void
     */
    public function test_index_a_collection_of_challenges()
    {
        $response = $this->json('GET', '/api/v1/challenges');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'difficulty',
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
    public function test_index_a_collection_of_challenges_paginate()
    {
        $response = $this->json('GET', '/api/v1/challenges?paginate=1');

        // Assert
        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    /**
     * Function to test show by id
     *
     * @return void
     */
    public function test_show_challenge_by_id()
    {
        $response = $this->json('GET', '/api/v1/challenges/'.$this->challenge->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'difficulty',
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
    public function test_error_show_challenge_by_id()
    {
        $response = $this->json('GET', '/api/v1/challenges/1500');

        $response->assertStatus(404)
            ->assertJsonStructure(['error', 'code']);
    }

    /**
     * Function to test store
     *
     * @return void
     */
    public function test_store_challenge()
    {
        $response = $this->json('POST', '/api/v1/challenges', [
            'title' => 'Test',
            'description' => 'Test',
            'difficulty' => 1,
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'difficulty',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ],
            ]);

        $data = $response->json();
        Challenges::where('id', $data['data']['id'])->delete();
    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_challenge()
    {
        $response = $this->json('POST', '/api/v1/challenges', [
            'description' => 'Test',
            'difficulty' => 1,
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
    public function test_update_challenge()
    {
        $response = $this->json('PUT', '/api/v1/challenges/'.$this->challenge->id, [
            'title' => 'New Test',
            'description' => 'Test',
            'difficulty' => 1,
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'difficulty',
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
    public function test_error_update_challenge()
    {
        $response = $this->json('PUT', '/api/v1/challenges/1500', [
            'title' => 'New Test',
            'description' => 'Test',
            'difficulty' => 1,
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
     public function test_delete_challenge()
    {
        $response = $this->json('DELETE', '/api/v1/challenges/'.$this->challenge->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    /**
     * Function to test error for delete
     *
     * @return void
     */
    public function test_error_delete_challenge()
    {
        $response = $this->json('DELETE', '/api/v1/challenges/1500');

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->challenge->delete();
        $this->user->delete();
    }

}
