<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected $baseUrl = 'api/users';

    public function test_get_all_with_pagination_empty()
    {
        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ]
        ]);
        $response->assertJsonFragment([
            'total' => 0
        ]);

    }

    public function test_get_all_with_pagination()
    {
        User::factory()->count(20)->create();

        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ]
        ]);
        $response->assertJsonFragment([
            'total' => 20,
            'current_page' => 1
        ]);
    }

    public function test_get_all_with_pagination_page_two()
    {
        User::factory()->count(15)->create();

        $response = $this->getJson("{$this->baseUrl}?page=2");

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonFragment([
            'total' => 15,
            'current_page' => 2
        ]);
    }
}
