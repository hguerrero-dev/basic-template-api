<?php

namespace App\Modules\Roles\Services;

use App\Modules\Core\Services\BaseService;
use App\Modules\Roles\DTOs\CreateRoleDTO;
use App\Modules\Roles\DTOs\UpdateRoleDTO;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Roles\Models\Role;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page') ?: 1;
        $perPage = $perPage ?? config('api.pagination.default', 15);

        $cacheKey = sprintf(
            '%s:s:%s:p:%s:pg:%s',
            Role::CACHE_KEY_LIST,
            $search,
            $perPage,
            $page
        );

        return $this->paginateAndCache(
            Role::with('permissions'),
            $cacheKey,
            [Role::CACHE_TAG],
            3600,
            [
                'search' => $search,
                'page' => $page,
                'perPage' => $perPage,
                'searchFields' => ['name']
            ]
        );
    }

    public function getByOne($id)
    {
        $cacheKey = sprintf('%s:id:%s', Role::CACHE_KEY_DETAIL, $id);

        return Cache::tags([Role::CACHE_TAG])->remember($cacheKey, 3600, function () use ($id) {
            return Role::with('permissions')->findOrFail($id);
        });
    }

    public function create(CreateRoleDTO $dto): Role
    {
        $role = DB::transaction(function () use ($dto) {
            $role = Role::create([
                'name' => $dto->name,
                'guard_name' => 'api'
            ]);

            $this->syncRolePermissions($role, ['permissions' => $dto->permissions]);

            return $role;
        });

        Cache::tags([Role::CACHE_TAG])->flush();

        return $role;
    }

    public function update(Role $role, UpdateRoleDTO $dto): Role
    {
        $updatedRole = DB::transaction(function () use ($role, $dto) {
            $role->update([
                'name' => $dto->name,
                'guard_name' => 'api'
            ]);

            $this->syncRolePermissions($role, ['permissions' => $dto->permissions]);

            return $role;
        });

        Cache::tags([Role::CACHE_TAG])->flush();

        return $updatedRole;
    }

    public function delete(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new Exception('No se puede eliminar un rol asignado a usuarios');
        }

        if (in_array($role->name, SystemRole::protectedRoles())) {
            throw new Exception('No se puede eliminar un rol protegido del sistema.');
        }

        $role->delete();

        Cache::tags([Role::CACHE_TAG])->flush();
    }

    protected function syncRolePermissions(Role $role, array $data): void
    {
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
            Cache::tags([Role::CACHE_TAG_PERMISSIONS])->flush();
        }
    }

    public function getAllPermissionsGrouped()
    {
        return Cache::tags([Role::CACHE_TAG_PERMISSIONS])->remember(Role::CACHE_KEY_PERMISSIONS_GROUPED, 86400, function () {
            return Permission::all()
                ->map(function ($permission) {
                    $permission->group_name = explode('.', $permission->name)[0];
                    return $permission;
                })
                ->groupBy('group_name')
                ->map(function ($group) {
                    return $group->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                        ];
                    });
                });
        });
    }
}
