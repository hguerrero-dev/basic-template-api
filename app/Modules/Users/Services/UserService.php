<?php

namespace App\Modules\Users\Services;

use App\Modules\Core\Services\BaseService;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Users\DTOs\CreateUserDTO;
use App\Modules\Users\DTOs\UpdateUserDTO;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page') ?: 1;
        $perPage = $perPage ?? config('api.pagination.default', 15);

        $cacheKey = sprintf(
            '%s:s:%s:p:%s:pg:%s',
            User::CACHE_KEY_LIST,
            $search,
            $perPage,
            $page
        );

        return $this->paginateAndCache(
            User::with('roles'),
            $cacheKey,
            [User::CACHE_TAG],
            3600,
            [
                'search' => $search,
                'perPage' => $perPage,
                'searchFields' => ['name', 'username', 'email'],
                'page' => $page
            ]
        );
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

    public function create(CreateUserDTO $dto)
    {
        $username = $this->generateUniqueUsername($dto->name, $dto->email);
        $user = User::create([
            'name' => $dto->name,
            'username' => $username,
            'email' => $dto->email ?? null,
            'password' => $this->hashPassword($dto->password),
            'status' => UserStatus::Active,
        ]);

        $this->manageRoles($user, $dto->roles);

        Cache::tags([User::CACHE_TAG])->flush();

        return $user;
    }

    public function update(UpdateUserDTO $dto)
    {
        $user = User::findOrFail($dto->id);

        $user->update([
            'name' => $dto->name,
            'email' => $dto->email ?? null,
            'status' => UserStatus::Active,
        ]);

        if ($dto->password) {
            $user->update([
                'password' => $this->hashPassword($dto->password),
            ]);
        }

        $this->manageRoles($user, $dto->roles);

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

    public function generateUniqueUsername(string $name, ?string $email = null): string
    {
        $baseString = $email ? explode('@', $email)[0] : $name;
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $baseString));

        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username ?: 'user' . uniqid();
    }
}
