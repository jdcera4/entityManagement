<?php

namespace Tests\Feature;

use App\Models\Companies;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CompaniesControllerTest extends TestCase
{
    //use RefreshDatabase;

    private User $user;
    private Companies $company;

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
        $this->company = Companies::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Function to test index
     *
     * @return void
     */
    public function test_index_a_collection_of_companies()
    {
        $response = $this->json('GET', '/api/v1/companies');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'image_path',
                        'location',
                        'industry',
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
    public function test_index_a_collection_of_companies_paginate()
    {
        $response = $this->json('GET', '/api/v1/companies?paginate=1');

        // Assert
        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    /**
     * Function to test show by id
     *
     * @return void
     */
    public function test_show_company_by_id()
    {
        $response = $this->json('GET', '/api/v1/companies/' . $this->company->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'image_path',
                    'location',
                    'industry',
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
    public function test_error_show_company_by_id()
    {
        $response = $this->json('GET', '/api/v1/companies/1500');

        $response->assertStatus(404)
            ->assertJsonStructure(['error', 'code']);
    }

    /**
     * Function to test store
     *
     * @return void
     */
    public function test_store_company()
    {
        $response = $this->json('POST', '/api/v1/companies', [
            'name' => 'Test',
            'image_path' => 'image',
            'location' => 'location',
            'industry' => 'industry',
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'image_path',
                    'location',
                    'industry',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ],
            ]);
        $data = $response->json();
        Companies::where('id', $data['data']['id'])->delete();
    }

    /**
     * Function to test error for store
     *
     * @return void
     */
    public function test_error_store_company()
    {
        $response = $this->json('POST', '/api/v1/companies', [
            'image_path' => 'image',
            'location' => 'location',
            'industry' => 'industry',
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
    public function test_update_company()
    {
        $response = $this->json('PUT', '/api/v1/companies/' . $this->company->id, [
            'name' => 'Test Update',
            'image_path' => 'image',
            'location' => 'location',
            'industry' => 'industry',
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'image_path',
                    'location',
                    'industry',
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
    public function test_error_update_company()
    {
        $response = $this->json('PUT', '/api/v1/companies/1500', [
            'name' => 'Test Update',
            'image_path' => 'image',
            'location' => 'location',
            'industry' => 'industry',
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
    public function test_delete_company()
    {
        $response = $this->json('DELETE', '/api/v1/companies/' . $this->company->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    /**
     * Function to test error for delete
     *
     * @return void
     */
    public function test_error_delete_company()
    {
        $response = $this->json('DELETE', '/api/v1/companies/1500');

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'error', 'code']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->company->delete();
        $this->user->delete();
    }
}
