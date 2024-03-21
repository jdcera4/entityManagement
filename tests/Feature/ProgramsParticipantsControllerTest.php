<?php

namespace Tests\Feature;

use App\Models\Challenges;
use App\Models\Programs;
use App\Models\ProgramsParticipants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramsParticipantsControllerTest extends TestCase
{
    private User $user;
    Private ProgramsParticipants $program_participant;

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
        $this->program_participant = ProgramsParticipants::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Function to test index
     *
     * @return void
     */
    public function test_index_a_collection_of_programs_participants()
    {
        $response = $this->json('GET', '/api/v1/programs-participants');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'entity_type',
                        'program_id',
                        'entity_id',
                        'program' => [
                            'id',
                            'title',
                            'description'
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
    public function test_index_a_collection_of_programs_participants_paginate()
    {
        $response = $this->json('GET', '/api/v1/programs-participants?paginate=1&items=3');

        // Assert
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    /**
     * Function to test show by id
     *
     * @return void
     */
    public function test_show_program_participant_by_id()
    {
        $response = $this->json('GET', '/api/v1/programs-participants/' . $this->program_participant->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'entity_type',
                    'program_id',
                    'entity_id',
                    'program' => [
                        'id',
                        'title',
                        'description'
                    ]
                ],
            ]);
    }

    /**
     * Function to test error for show by id
     *
     * @return void
     */
    public function test_error_show_program_participant_by_id()
    {
        $response = $this->json('GET', '/api/v1/programs-participants/1500');

        $response->assertStatus(404)
            ->assertJsonStructure(['error', 'code']);
    }

    /**
     * Function to test store
     *
     * @return void
     */
    public function test_store_program_participant()
    {
        $program = Programs::factory()->create();
        $challenge = Challenges::factory()->create();
        $response = $this->json('POST', '/api/v1/programs-participants', [
            "program_id" => $program->id,
            "entity_id" => $challenge->id,
            "entity_type" => "challenge" //Este valor solo puede recibir user, challenge o comapany
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'entity_type',
                    'program_id',
                    'entity_id',
                    'program' => [
                        'id',
                        'title',
                        'description'
                    ]
                ],
            ]);

        $challenge->delete();
        $program->delete();
        $data = $response->json();
        ProgramsParticipants::where('id', $data['data']['id'])->delete();
    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_program_participant()
    {
        $program = Programs::factory()->create();
        $challenge = Challenges::factory()->create();

        $response = $this->json('POST', '/api/v1/programs-participants', [
            "entity_id" => $challenge->id,
            "entity_type" => "challenge" //Este valor solo puede recibir user, challenge o comapany
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);

        $challenge->delete();
        $program->delete();

    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_program_participant_entity_no_allowed()
    {
        $program = Programs::factory()->create();
        $challenge = Challenges::factory()->create();

        $response = $this->json('POST', '/api/v1/programs-participants', [
            "program_id" => $program->id,
            "entity_id" => $challenge->id,
            "entity_type" => "progra" //Este valor solo puede recibir user, challenge o comapany
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);

        $challenge->delete();
        $program->delete();
    }

    /**
     * Function to test update
     *
     * @return void
     */
    public function test_update_program()
    {
        $response = $this->json('PUT', '/api/v1/programs-participants/' . $this->program_participant->id, [
            "program_id" => $this->program_participant->program->id,
            "entity_id" => $this->program_participant->entity_id,
            "entity_type" => $this->program_participant->entity_type //Este valor solo puede recibir user, challenge o comapany
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'entity_type',
                    'program_id',
                    'entity_id',
                    'program' => [
                        'id',
                        'title',
                        'description'
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
        $response = $this->json('PUT', '/api/v1/programs-participants/1500', [
            "program_id" => 1,
            "entity_id" => 1,
            "entity_type" => "challenge" //Este valor solo puede recibir user, challenge o comapany
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
        $response = $this->json('DELETE', '/api/v1/programs-participants/' . $this->program_participant->id);

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
        $response = $this->json('DELETE', '/api/v1/programs-participants/1500');

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->program_participant->delete();
        $this->user->delete();
    }
}
