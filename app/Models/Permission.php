<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withTimestamps();
    }

    // Helper methods
    public static function getByModule($module)
    {
        return static::where('module', $module)->get();
    }

    public static function getModules()
    {
        return static::distinct('module')->pluck('module');
    }
}
