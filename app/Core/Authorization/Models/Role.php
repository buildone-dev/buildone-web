<?php

declare(strict_types=1);

namespace App\Core\Authorization\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $label
 * @property list<string>|null $permissions
 */
class Role extends Model
{
    public const SUPER_ADMIN = 'super_admin';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'label',
        'permissions',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->name === self::SUPER_ADMIN) {
            return true;
        }

        return in_array($permission, $this->permissions ?? [], true);
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
