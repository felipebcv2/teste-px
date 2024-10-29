<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Interfaces\AuthServiceInterface;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/token",
     *     summary="Gerar Token de Acesso",
     *     description="Realiza o login do usuário e retorna um token de acesso.",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="teste@px.com"),
     *             @OA\Property(property="password", type="string", format="password", example="@3das$dasst341#08787jh")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token gerado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     )
     * )
     */
    public function token(LoginRequest $request)
    {       
        $credentials = $request->only('email', 'password');

        $token = $this->authService->token($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        return response()->json([
            'token' => "Bearer $token",
        ], 200);
    }
}
