<?php

namespace App\Modules\Roles\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Modules\Roles\Models\Role;
use App\Modules\Core\Controllers\BaseController;
use App\Modules\Roles\Requests\CreateRoleRequest;
use App\Modules\Roles\Requests\UpdateRoleRequest;
use App\Modules\Roles\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    public function __construct(protected RoleService $roleService) {}

    public function index(Request $request): JsonResponse
    {
        $roles = $this->roleService->getAll(
            $request->input('search'),
            $request->input('per_page')
        );

        return $this->successResponse(
            $roles,
            'Roles obtenidos correctamente'
        );
    }

    public function show($id): JsonResponse
    {
        $role = $this->roleService->getByOne($id);

        return $this->successResponse($role, 'Rol obtenido exitosamente');
    }

    public function create(): JsonResponse
    {
        $permissions = $this->roleService->getAllPermissionsGrouped();

        return $this->successResponse($permissions, 'Permisos obtenidos exitosamente');
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        $role = $this->roleService->create($data);

        return $this->successResponse($role, 'Rol creado correctamente', 201);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();

        $updatedRole = $this->roleService->update($role, $data);

        return $this->successResponse($updatedRole, 'Rol actualizado correctamente');
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->roleService->delete($role);
            return $this->successResponse(null, 'Rol eliminado correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'ROLE_DELETE_ERROR', 400);
        }
    }
}
