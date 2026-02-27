<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Core\Controllers\BaseController;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Modules\Users\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Modules\Users\Resources\UserResource;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService) {}

    public function getFormOptions(): JsonResponse
    {
        $statuses = array_map(fn($status) => [
            'label' => ucfirst($status->value),
            'value' => $status->value,
        ], UserStatus::cases());

        $roles = Role::get()->map(fn($role) => [
            'label' => ucfirst($role->name),
            'value' => $role->id,
        ]);

        return $this->successResponse([
            'statuses' => $statuses,
            'roles' => $roles
        ], 'Opciones de formulario obtenidas correctamente');
    }

    public function me(Request $request)
    {
        return $this->successResponse([
            'user' => $request->user(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name')
        ], 'Información del usuario obtenida correctamente');
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAll(
            $request->input('search'),
            $request->input('per_page')
        );

        return $this->successResponse(
            UserResource::collection($users),
            'Usuarios obtenidos correctamente'
        );
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->getByOne($id);

        return $this->successResponse(
            new UserResource($user),
            'Usuario obtenido correctamente'
        );
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        return $this->successResponse(
            new UserResource($user),
            'Usuario creado correctamente',
            201
        );
    }

    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->update($id, $data);

        return $this->successResponse(
            new UserResource($user),
            'Usuario actualizado correctamente'
        );
    }


    public function destroy($id): JsonResponse
    {
        $this->userService->delete($id);

        return $this->successResponse(null, 'Usuario eliminado correctamente');
    }
}
