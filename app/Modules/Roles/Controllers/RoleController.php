<?php

namespace App\Modules\Roles\Controllers;

use App\Modules\Core\Controllers\BaseController;
use App\Modules\Roles\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseController
{
    public function __construct(protected RoleService $roleService) {}

    public function index(): JsonResponse
    {
        $roles = $this->roleService->getAll();

        return response()->json([
            'message' => 'Roles obtenidos exitosamente',
            'data'    => $roles
        ]);
    }

    public function store()
    {
        return Permission::all()->map(fn($permission) => [
            'label' => $permission->name,
            'value' => $permission->name,
        ]);
    }
}
