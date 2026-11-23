<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
    'name',
    'description',
    'status',
    'permissions',
];

protected function casts(): array
{
    return [
        'permissions' => 'array',
    ];
}

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

   public function hasPermission(string $permission): bool
{
    if ($this->name === 'Admin') {
        return true;
    }

    return (bool) ($this->permissions[$permission] ?? false);
}
}
