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

        if (!$identifier) return $this->errorResponse('Username or email is required.', 'INVALID_CREDENTIALS', 422);

        $password = $credentials['password'] ?? null;

        try {
            $result = $this->authService->authenticate(
                $identifier,
                $password
            );

            return $this->successResponse($result, 'Logged in successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'AUTHENTICATION_FAILED', 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Logged out successfully');
    }
}
