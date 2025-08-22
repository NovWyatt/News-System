<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships

    public function draftArticles()
    {
        return $this->articles()->draft();
    }

    public function featuredArticles()
    {
        return $this->articles()->featured();
    }

    // Article stats
    public function getTotalArticlesAttribute()
    {
        return $this->articles()->count();
    }

    public function getPublishedArticlesCountAttribute()
    {
        return $this->publishedArticles()->count();
    }

    public function getDraftArticlesCountAttribute()
    {
        return $this->draftArticles()->count();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('assigned_by', 'assigned_at')
                    ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // News-specific relationships
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function publishedArticles()
    {
        return $this->articles()->where('status', 'published');
    }

    // Helper methods
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains($role);
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->attach($role->id, [
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->detach($role->id);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isEditor()
    {
        return $this->hasRole('editor');
    }

    public function canPublish()
    {
        return $this->hasPermission('articles.publish');
    }

    public function canManageUsers()
    {
        return $this->hasPermission('users.manage');
    }

    // Update last login
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);

        ActivityLog::log(
            'user.login',
            'Đăng nhập vào hệ thống',
            $this
        );
    }
}
