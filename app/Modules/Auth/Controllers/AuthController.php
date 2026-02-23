<?php

namespace App\Modules\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Core\Controllers\BaseController;

class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $identifier = $credentials['username'] ?? $credentials['email'] ?? null;

        if (!$identifier) return response()->json(['message' => 'Username or email is required.'], 422);

        $password = $credentials['password'] ?? null;

        try {
            $result = $this->authService->authenticate(
                $identifier,
                $password
            );

            return response()->json([
                'access_token' => $result['token'],
                'user' => $result['user']
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
