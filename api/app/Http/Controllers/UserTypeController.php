<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserTypeResource;

/**
 * @OA\Schema(
 *     schema="UserType",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Administrator"),
 *         @OA\Property(property="description", type="string", example="Full access to the system"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-16T12:00:00Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-16T12:30:00Z"),
 *     }
 * )
 */

class UserTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user-types",
     *     summary="Lista todos os tipos de usuário",
     *     tags={"Tipos de Usuários"},
     *     description="Retorna a lista de tipos de usuário disponíveis",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserType")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return UserTypeResource::collection(UserType::get());
    }
}
