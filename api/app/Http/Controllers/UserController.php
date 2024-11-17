<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuário",
 *     description="Representação de um usuário",
 *     @OA\Property(property="id", type="integer", example=1, description="Identificador único do usuário"),
 *     @OA\Property(property="id_user_type", type="integer", example=1, description="ID do tipo de usuário, relacionado à tabela user_type"),
 *     @OA\Property(property="name", type="string", example="João da Silva", description="Nome completo do usuário"),
 *     @OA\Property(property="email", type="string", format="email", example="joao.silva@example.com", description="E-mail do usuário"),
 *     @OA\Property(property="document_type", type="string", enum={"cpf", "cnpj"}, example="cpf", description="Tipo de documento (CPF ou CNPJ)"),
 *     @OA\Property(property="document", type="string", maxLength=20, example="12345678900", description="Número do documento (CPF ou CNPJ)"),
 *     @OA\Property(property="telephone", type="string", maxLength=20, nullable=true, example="1122334455", description="Telefone fixo do usuário"),
 *     @OA\Property(property="cellphone", type="string", maxLength=20, nullable=true, example="11987654321", description="Telefone celular do usuário"),
 *     @OA\Property(property="business", type="string", nullable=true, example="Empresa XYZ", description="Razão social ou nome fantasia, se aplicável"),
 *     @OA\Property(property="status", type="string", enum={"A", "I"}, example="A", description="Status do usuário ('A' - Ativo, 'I' - Inativo)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z", description="Data e hora de criação do registro"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z", description="Data e hora da última atualização do registro")
 * )
 *
 * @OA\Schema(
 *     schema="UserCreate",
 *     type="object",
 *     title="Criação de Usuário",
 *     description="Modelo para criar um novo usuário",
 *     @OA\Property(property="id", type="integer", example=1, description="Identificador único do usuário"),
 *     @OA\Property(property="id_user_type", type="integer", example=1, description="ID do tipo de usuário, relacionado à tabela user_type"),
 *     @OA\Property(property="name", type="string", example="João da Silva", description="Nome completo do usuário"),
 *     @OA\Property(property="email", type="string", format="email", example="joao.silva@example.com", description="E-mail do usuário"),
 *     @OA\Property(property="password", type="string", format="password", example="********", description="Senha do usuário"),
 *     @OA\Property(property="document_type", type="string", enum={"cpf", "cnpj"}, example="cpf", description="Tipo de documento (CPF ou CNPJ)"),
 *     @OA\Property(property="document", type="string", maxLength=20, example="12345678900", description="Número do documento (CPF ou CNPJ)"),
 *     @OA\Property(property="telephone", type="string", maxLength=20, nullable=true, example="1122334455", description="Telefone fixo do usuário"),
 *     @OA\Property(property="cellphone", type="string", maxLength=20, nullable=true, example="11987654321", description="Telefone celular do usuário"),
 *     @OA\Property(property="business", type="string", nullable=true, example="Empresa XYZ", description="Razão social ou nome fantasia, se aplicável"),
 *     @OA\Property(property="status", type="string", enum={"A", "I"}, example="A", description="Status do usuário ('A' - Ativo, 'I' - Inativo)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z", description="Data e hora de criação do registro"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z", description="Data e hora da última atualização do registro")
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obtém a lista de usuários paginada",
     *     tags={"Usuários"},
     *     description="Retorna uma lista de usuários com suporte a paginação",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100),
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://api.local/users?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://api.local/users?page=10"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", example="http://api.local/users?page=2"),
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);

        $users = User::with('userType')->paginate($perPage);

        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Detalhes do usuário",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function show($id)
    {
        $user = User::with('userType')->findOrFail($id);
        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Criar usuário",
     *     tags={"Usuários"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualizar usuário",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Deletar usuário",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuário deletado com sucesso")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }
}
