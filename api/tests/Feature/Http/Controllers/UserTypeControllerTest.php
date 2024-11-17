<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testar a listagem de tipos de usuários.
     */
    public function test_can_list_user_types()
    {
        UserType::factory()->count(5)->create();

        $response = $this->getJson('/api/user-types');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name']
                     ]
                 ]);
    }
}
