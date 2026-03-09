<?php

namespace App\Modules\Roles\Services;

use Exception;
use App\Modules\Roles\Models\Role;
use Illuminate\Pagination\Paginator;
use App\Modules\Core\Services\BaseService;
use App\Modules\Roles\DTOs\CreateRoleDTO;
use App\Modules\Roles\DTOs\UpdateRoleDTO;
use App\Modules\Roles\Enums\SystemRole;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $page = Paginator::resolveCurrentPage('page') ?: 1;
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
                'description' => $dto->description,
                'guard_name' => $dto->guard_name
            ]);

            $this->syncRolePermissions($role, ['permissions' => $dto->permissions]);

            return $role;
        });

        Cache::tags([Role::CACHE_TAG])->flush();

        return $role;
    }

    public function update(UpdateRoleDTO $dto): Role
    {
        $role = Role::findOrFail($dto->id);

        $updatedRole = DB::transaction(function () use ($role, $dto) {
            $role->update([
                'name' => $dto->name ?? $role->name,
                'description' => $dto->description ?? $role->description,
                'guard_name' => $dto->guard_name ?? $role->guard_name
            ]);

            if (isset($dto->permissions)) {
                $this->syncRolePermissions($role, ['permissions' => $dto->permissions]);
            }

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
            // Retrieve permissions using their ID. Since syncPermissions accepts IDs if provided numerically.
            // Spatie handles integer IDs by extracting them natively, but if the guard isn't matched perfectly, it errors out.
            // Explicitly fetching models ensures we attach correct permissions for that guard to sidestep Spatie string quirks.
            $numericIds = array_map('intval', $data['permissions']);
            $permissionsToSync = Permission::whereIn('id', $numericIds)->where('guard_name', $role->guard_name)->get();

            $role->syncPermissions($permissionsToSync);
            Cache::tags([Role::CACHE_TAG_PERMISSIONS])->flush();
        }
    }

    public function getAllPermissionsGrouped(?string $guard = null)
    {
        return Cache::tags([Role::CACHE_TAG_PERMISSIONS])->remember(
            Role::CACHE_KEY_PERMISSIONS_GROUPED . ($guard ? ":$guard" : ''),
            86400,
            function () use ($guard) {
                $query = Permission::query();
                if ($guard) {
                    $query->where('guard_name', $guard);
                }
                return $query->get()
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
                                'guard_name' => $p->guard_name,
                            ];
                        });
                    });
            }
        );
    }
}
