<?php

namespace Tests\Feature;

use Tests\TestCase;

class DiscountControllerTest extends TestCase
{
    /**
     * Cenário de sucesso: usuário premium com produtos válidos.
     */
    public function test_calculate_discount_for_premium_user()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => 100],
                ['price' => 200]
            ],
            'user' => [
                'type' => 'premium'
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'total' => 270.0 // 10% de desconto em 300
            ]);
    }

    /**
     * Cenário de sucesso: usuário regular com produtos válidos.
     */
    public function test_calculate_discount_for_regular_user()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => 50],
                ['price' => 150]
            ],
            'user' => [
                'type' => 'regular'
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'total' => 190.0 // 5% de desconto em 200
            ]);
    }

    /**
     * Cenário de sucesso: usuário sem tipo específico (sem desconto).
     */
    public function test_calculate_discount_for_user_without_type()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => 300]
            ],
            'user' => [
                'type' => 'unknown'
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'user.type' => ['O user.type selecionado é inválido.']
            ]);
    }

    /**
     * Cenário de falha: ausência de produtos.
     */
    public function test_validation_error_when_products_are_missing()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'user' => [
                'type' => 'premium'
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('messages.products', ['O campo products é obrigatório.']);
    }

    /**
     * Cenário de falha: ausência do campo user.type.
     */
    public function test_validation_error_when_user_type_is_missing()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => 100]
            ],
            'user' => []
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'user.type' => ['O campo user.type é obrigatório.'],
            ]);
    }

    /**
     * Cenário de falha: produto com preço inválido.
     */
    public function test_validation_error_for_invalid_product_price()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => -50] // Preço inválido
            ],
            'user' => [
                'type' => 'premium'
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'products.0.price' => ['O campo products.0.price deve ser pelo menos 0.']
            ]);
    }

    /**
     * Cenário de falha: usuário com tipo inválido.
     */
    public function test_validation_error_for_invalid_user_type()
    {
        $response = $this->postJson('/api/calculate-discount', [
            'products' => [
                ['price' => 100]
            ],
            'user' => [
                'type' => 'invalid-type'
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'user.type' => ['O user.type selecionado é inválido.']
            ]);
    }
}
