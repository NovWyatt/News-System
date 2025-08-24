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
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
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

    public function getRecentActivityLogsAttribute($limit = 10)
    {
        return $this->activityLogs()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getTotalActivityLogsAttribute()
    {
        return $this->activityLogs()->count();
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

    public function getDisplayNameAttribute()
    {
        return $this->username ?: explode('@', $this->email)[0];
    }

    public function getIsVerifiedAttribute()
    {
        return ! is_null($this->email_verified_at);
    }

    public function getRecentActivityAttribute()
    {
        return $this->activityLogs()
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getTotalActivityCountAttribute()
    {
        return $this->activityLogs()->count();
    }

    public function getLastActivityAttribute()
    {
        $lastLog = $this->activityLogs()->latest()->first();
        return $lastLog ? $lastLog->created_at : null;
    }

// Scope methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    public function scopeWithRole($query, $roleName)
    {
        return $query->whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
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
