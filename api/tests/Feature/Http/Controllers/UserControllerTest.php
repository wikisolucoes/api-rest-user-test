<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testar a listagem de usuários com paginação.
     */
    public function test_can_list_users()
    {
        User::factory()->count(10)->create();
        $response = $this->getJson('/api/users?per_page=5');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'document_type', 'document', 'status']
                     ],
                     'meta',
                     'links'
                 ]);
    }

    /**
     * Testar exibição de um único usuário.
     */
    public function test_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $user->id,
                         'name' => $user->name,
                         'email' => $user->email,
                     ]
                 ]);
    }

    /**
     * Testar criação de um novo usuário.
     */
    public function test_can_create_a_user()
    {
        $userType = UserType::factory()->create();
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'document_type' => 'cpf',
            'document' => '12345678910',
            'id_user_type' => $userType->id,
            'status' => 'A'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'name' => $userData['name'],
                         'email' => $userData['email'],
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    /**
     * Testar validação ao criar usuário com dados inválidos.
     */
    public function test_validation_error_when_creating_a_user()
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'document_type', 'document', 'id_user_type']);
    }

    /**
     * Testar atualização de um usuário existente.
     */
    public function test_can_update_a_user()
    {
        $userType = UserType::factory()->create();
        $user = User::factory()->create(['id_user_type' => $userType->id]);

        $updateData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'document_type' => 'cpf',
            'document' => '12345678910',
            'id_user_type' => $userType->id,
            'status' => 'A'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => $updateData['name'],
                         'email' => $updateData['email'],
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updateData['name']
        ]);
    }

    /**
     * Testar deleção de um usuário existente.
     */
    public function test_can_delete_a_user()
    {
        $userType = UserType::factory()->create();
        $user = User::factory()->create(['id_user_type' => $userType->id]);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Usuário deletado com sucesso']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
