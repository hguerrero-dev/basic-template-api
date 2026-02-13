<?php

namespace App\Modules\Roles\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Modules\Core\Controllers\BaseController;
use App\Modules\Roles\Requests\CreateRoleRequest;
use App\Modules\Roles\Requests\UpdateRoleRequest;
use App\Modules\Roles\Services\RoleService;

class RoleController extends BaseController
{
    public function __construct(protected RoleService $roleService) {}

    public function index(): JsonResponse
    {
        $roles = $this->roleService->getAll();

        return response()->json([
            'message' => 'Roles obtenidos exitosamente',
            'data'    => $roles
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $role = $this->roleService->getByOne($id);

        return response()->json([
            'message' => 'Rol obtenido exitosamente',
            'data'    => $role
        ], 200);
    }

    public function create(): JsonResponse
    {
        $permissions = $this->roleService->getAllPermissionsGrouped();

        return response()->json([
            'message' => 'Permisos obtenidos exitosamente',
            'data'    => $permissions
        ], 200);
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        $role = $this->roleService->create($data);

        return response()->json([
            'message' => 'Rol creado correctamente',
            'data'    => $role
        ], 201);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();

        $updatedRole = $this->roleService->update($role, $data);

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'data'    => $updatedRole
        ], 200);
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->roleService->delete($role);

            return response()->json([
                'message' => 'Rol eliminado correctamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
