<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Core\Controllers\BaseController;
use App\Modules\Users\DTOs\CreateUserDTO;
use App\Modules\Users\DTOs\UpdateUserDTO;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Modules\Users\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Support\Facades\Storage;

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
            'estados' => $statuses,
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
        $dto = new CreateUserDTO(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('username'),
            $request->input('roles', [])
        );
        $user = $this->userService->create($dto);

        return $this->successResponse(
            new UserResource($user),
            'Usuario creado correctamente',
            201
        );
    }


    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        $dto = new UpdateUserDTO(
            $id,
            $request->input('name'),
            $request->input('email'),
            $request->input('username'),
            $request->input('roles', []),
            $request->input('password')
        );
        $user = $this->userService->update($dto);

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

    public function uploadAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Si el usuario ya tiene un avatar, lo eliminamos de MinIO
        if ($user->avatar) {
            Storage::disk('minio')->delete($user->avatar);
        }

        // Subimos el nuevo archivo al disco 'minio' en la carpeta 'avatars'
        $path = $request->file('avatar')->store('avatars', 'minio');

        // Actualizamos el usuario
        $user->update(['avatar' => $path]);

        // Evitar el error P1013 del editor
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('minio');
        $url = $disk->url($path);

        return $this->successResponse([
            'avatar_url' => $url,
            'path' => $path
        ], 'Avatar actualizado correctamente');
    }
}
