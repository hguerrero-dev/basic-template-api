<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Core\Controllers\BaseController;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Modules\Users\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService) {}

    public function getFormOptions(): JsonResponse
    {

        $statuses = array_map(fn($status) => [
            'label' => ucfirst($status->value),
            'value' => $status->value,
        ], UserStatus::cases());

        $roles = Role::pluck('name')->map(fn($role) => [
            'label' => ucfirst($role),
            'value' => $role,
        ]);

        return response()->json([
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }

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

    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->update($id, $data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data'    => $user
        ]);
    }
}
