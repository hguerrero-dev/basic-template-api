<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Core\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class LoginController extends BaseController
{
    public function authenticate(LoginRequest $request, AuthService $authService): JsonResponse
    {

        $request->validated();

        $identifier = $request->input('username') ?: $request->input('email');

        if (!$identifier) return response()->json(['message' => 'Username or email is required.'], 422);

        $result = $authService->authenticate(
            $identifier,
            $request->input('password')
        );

        return response()->json([
            'access_token' => $result['token'],
            'user' => $result['user']
        ]);
    }
}
