<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Core\Controllers\BaseController;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Requests\CreateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService) {}

    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();

        return response()->json([
            'message' => 'Usuarios obtenidos exitosamente',
            'data'    => $users
        ]);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        // 1. request->validated() nos da solo los datos limpios
        $data = $request->validated();

        // 2. Delegamos la lógica al servicio
        $user = $this->userService->create($data);

        // 3. Retornamos respuesta (201 Created)
        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data'    => $user
        ], 201);
    }
}
