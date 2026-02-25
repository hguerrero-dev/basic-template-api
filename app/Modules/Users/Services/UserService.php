<?php

namespace App\Modules\Users\Services;

use App\Modules\Core\Services\BaseService;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {

        $page = request()->input('page', 1);
        $cacheKey = sprintf(
            '%s:s:%s:p:%s:pg:%s',
            User::CACHE_KEY_LIST,
            $search,
            $perPage,
            $page
        );

        return Cache::tags([User::CACHE_TAG])->remember($cacheKey, 3600, function () use ($search, $perPage) {
            return $this->paginate(User::with('roles'), [
                'search' => $search,
                'perPage' => $perPage,
                'searchFields' => ['name', 'username', 'email']
            ]);
        });

        return $this->paginate(User::with('roles'), [
            'search' => $search,
            'perPage' => $perPage,
            'searchFields' => ['name', 'username', 'email']
        ]);
    }

    public function getByOne($id)
    {
        $cacheKey = sprintf('%s:id:%s', User::CACHE_KEY_DETAIL, $id);

        $user = Cache::tags([User::CACHE_TAG])->remember($cacheKey, 3600, function () use ($id) {
            return User::withTrashed()->with('roles')->findOrFail($id);
        });

        if ($user->trashed()) {
            throw new \Exception('User is deleted');
        }

        if ($user->status === UserStatus::Inactive) {
            throw new \Exception('User is inactive');
        }

        return $user;
    }

    public function create($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'password' => $this->hashPassword($data['password']),
            'status' => $data['status'] ?? UserStatus::Active,
        ]);

        $this->manageRoles($user, $data['roles'] ?? []);

        Cache::tags([User::CACHE_TAG])->flush();

        return $user;
    }

    public function update($id, $data)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'status' => $data['status'] ?? UserStatus::Active,
        ]);

        if (isset($data['password'])) {
            $user->update([
                'password' => $this->hashPassword($data['password']),
            ]);
        }

        $this->manageRoles($user, $data['roles'] ?? []);

        Cache::tags([User::CACHE_TAG])->flush();

        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Cache::tags([User::CACHE_TAG])->flush();
    }

    protected function manageRoles(User $user, array $roles)
    {
        if ($roles) {
            $user->syncRoles($roles);
        } elseif ($user->roles()->count() === 0) {
            $user->assignRole(SystemRole::Customer->value);
        }

        $user->touch(); // => Force update of updated_at to trigger model events and cache invalidation if roles are changed
    }

    protected function hashPassword(string $password): string
    {
        if (Hash::needsRehash($password)) {
            return Hash::make($password);
        }

        return $password;
    }
}
