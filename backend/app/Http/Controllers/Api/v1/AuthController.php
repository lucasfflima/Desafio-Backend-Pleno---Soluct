<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\RegisterRequest;
use App\Http\Requests\Api\v1\LoginRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user'    => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->validated());

            return response()->json([
                'message' => 'Login realizado com sucesso.',
                'token'   => $token,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout(Auth::user());

        return response()->json([
            'message' => 'Logout efetuado com sucesso.',
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json(new UserResource(Auth::user()));
    }
}
