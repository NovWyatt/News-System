<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system_role',
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withPivot('assigned_by', 'assigned_at', 'expires_at')
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withTimestamps();
    }

    // Helper methods
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        return $this->permissions()->attach($permission->id);
    }

    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        return $this->permissions()->detach($permission->id);
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return $this->permissions->contains($permission);
    }
}
