<?php

namespace App\Modules\Roles\Services;

use App\Modules\Roles\Enums\SystemRole;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAll()
    {
        return Role::with('permissions')->get();
    }

    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'api'
            ]);

            $this->syncRolePermissions($role, $data);

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
