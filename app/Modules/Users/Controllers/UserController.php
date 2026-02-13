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

        return response()->json([
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }

    public function index(Request $request)
    {
        // => read this from config/api.php (global config for API)
        $default = config('api.pagination.default');
        $max     = config('api.pagination.max');

        $perPage = (int) $request->input('per_page', $default);
        $search  = $request->input('search');

        if ($perPage > $max) $perPage = $max;

        $users = $this->userService->getAll($search, $perPage);

        return UserResource::collection($users);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->getByOne($id);

        return response()->json([
            'message' => 'Usuario obtenido exitosamente',
            'data'    => new UserResource($user)
        ]);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->update($id, $data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data'    => new UserResource($user)
        ]);
    }


    public function destroy($id): JsonResponse
    {
        $this->userService->delete($id);

        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ]);
    }
}
