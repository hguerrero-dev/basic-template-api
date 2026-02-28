<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\DTOs\LoginDTO;
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
        $dto = new LoginDTO($request->validated());

        if (!$dto->identifier) {
            return $this->errorResponse(
                'Debes proporcionar un nombre de usuario o correo electrónico para iniciar sesión.',
                'IDENTIFIER_REQUIRED',
                422
            );
        }

        try {
            $result = $this->authService->authenticate(
                $dto->identifier,
                $dto->password
            );

            return $this->successResponse($result, 'Inicio de sesión exitoso');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'AUTHENTICATION_FAILED', 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Cierre de sesión exitoso');
    }
}
