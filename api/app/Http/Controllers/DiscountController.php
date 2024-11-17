<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Calcular o desconto baseado nos produtos e no tipo de usuário.
     *
     * @OA\Post(
     *     path="/api/calculate-discount",
     *     summary="Calcular desconto total",
     *     description="Retorna o valor total com desconto aplicado, baseado no tipo do usuário e nos produtos informados.",
     *     tags={"Desconto"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"products", "user"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"price"},
     *                     @OA\Property(property="price", type="number", format="float", example=100.50)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 required={"type"},
     *                 @OA\Property(property="type", type="string", example="premium")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Valor total com desconto aplicado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total", type="number", format="float", example=270.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro nos dados fornecidos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Dados inválidos.")
     *         )
     *     )
     * )
     */
    public function calculateDiscount(Request $request)
    {
        try {
            $data = $request->validate([
                'products' => 'required|array|min:1',
                'products.*.price' => 'required|numeric|min:0',
                'user.type' => 'required|string|in:premium,regular'
            ]);

            $discountRates = [
                'premium' => 0.10,
                'regular' => 0.05,
                'default' => 0.0
            ];

            $userType = $data['user']['type'];
            $discount = $discountRates[$userType] ?? $discountRates['default'];

            $totalPrice = array_reduce($data['products'], function ($total, $product) {
                return $total + $product['price'];
            }, 0);

            $totalWithDiscount = $totalPrice * (1 - $discount);

            return response()->json([
                'total' => round($totalWithDiscount, 2)
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocorreu um erro inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
