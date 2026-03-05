<?php

namespace App\Modules\Users\Models;

use App\Modules\Users\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use App\Modules\Core\Traits\HasAudit;

use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasAudit, HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes;

    public const CACHE_TAG = 'users'; // => General cache tag for all user-related caching
    public const CACHE_KEY_LIST = 'user_list'; // => Base cache key for user list, can be combined with search and pagination parameters
    public const CACHE_KEY_DETAIL = 'user_detail'; // => Base cache key for individual user details, can be combined with user ID

    protected $guard_name = ['api', 'web'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Get and set the user's first name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'),
            set: fn(string $value) => mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'),
        );
    }
}
