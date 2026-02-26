<?php

namespace App\Modules\Roles\Services;

use App\Modules\Core\Services\BaseService;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Roles\Models\Role as ModelsRole;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $page = request()->input('page', 1);
        $perPage = $perPage ?? config('api.pagination.default', 15);

        $cacheKey = sprintf(
            '%s:s:%s:p:%s:pg:%s',
            ModelsRole::CACHE_KEY_LIST,
            $search,
            $perPage,
            $page
        );

        return Cache::tags([ModelsRole::CACHE_TAG])->remember($cacheKey, 3600, function () use ($search, $perPage, $cacheKey) {
            return $this->paginate(Role::with('permissions'), [
                'search' => $search,
                'perPage' => $perPage,
                'searchFields' => ['name']
            ]);
        });

        return $this->paginate(Role::with('permissions'), [
            'search' => $search,
            'perPage' => $perPage,
            'searchFields' => ['name']
        ]);
    }

    public function getByOne($id)
    {
        $cacheKey = sprintf('%s:id:%s', ModelsRole::CACHE_KEY_DETAIL, $id);

        $role = Cache::tags([ModelsRole::CACHE_TAG])->remember($cacheKey, 3600, function () use ($id) {
            return Role::with('permissions')->findOrFail($id);
        });

        if (!$role) {
            throw new Exception('Role not found');
        }

        return $role;
    }

    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'api'
            ]);

            $this->syncRolePermissions($role, $data);

            Cache::tags([ModelsRole::CACHE_TAG])->flush();

            return $role;
        });
    }

    public function update(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'name' => $data['name'],
                'guard_name' => 'api'
            ]);

            $this->syncRolePermissions($role, $data);

            Cache::tags([ModelsRole::CACHE_TAG])->flush();

            return $role;
        });
    }

    public function delete(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new Exception('No se puede eliminar un rol asignado a usuarios');
        }

        if (in_array($role->name, SystemRole::protectedRoles())) {
            throw new Exception('No se puede eliminar un rol protegido del sistema.');
        }

        Cache::tags([ModelsRole::CACHE_TAG])->flush();

        $role->delete();
    }

    protected function syncRolePermissions(Role $role, array $data): void
    {
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
    }

    public function getAllPermissionsGrouped()
    {
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
    }
}
