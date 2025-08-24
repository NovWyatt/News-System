<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public static function log($action, $description = null, $entity = null, $metadata = [])
    {
        return static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id'   => $entity ? $entity->id : null,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'metadata'    => $metadata,
        ]);
    }

    // Scopes
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->where('created_at', '>=', now()->startOfWeek());
    }

    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', now()->startOfMonth());
    }

// Helper methods
    public function getActionTypeAttribute()
    {
        $actionParts = explode('.', $this->action);
        return $actionParts[0] ?? 'unknown';
    }

    public function getActionNameAttribute()
    {
        $actionParts = explode('.', $this->action);
        return $actionParts[1] ?? 'unknown';
    }

    public function getActionIconAttribute()
    {
        $actionType = $this->action_type;

        $icons = [
            'user'     => 'ph  ph-user',
            'article'  => 'ph  ph-article',
            'login'    => 'ph  ph-sign-in',
            'logout'   => 'ph  ph-sign-out',
            'password' => 'ph  ph-lock',
            'profile'  => 'ph  ph-user-gear',
            'system'   => 'ph  ph-gear',
            'default'  => 'ph  ph-activity',
        ];

        return $icons[$actionType] ?? $icons['default'];
    }

    public function getActionColorAttribute()
    {
        $actionType = $this->action_type;

        $colors = [
            'user'     => 'var(--c-blue-500)',
            'article'  => 'var(--c-green-500)',
            'login'    => 'var(--c-green-500)',
            'logout'   => 'var(--c-orange-500)',
            'password' => 'var(--c-red-500)',
            'profile'  => 'var(--c-purple-500)',
            'system'   => 'var(--c-orange-500)',
            'default'  => 'var(--c-text-tertiary)',
        ];

        return $colors[$actionType] ?? $colors['default'];
    }

// Static helper for common log queries
    public static function getRecentActivity($limit = 20)
    {
        return static::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public static function getTopUsers($limit = 10, $days = 30)
    {
        return static::select('user_id')
            ->with('user')
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as activity_count')
            ->orderByDesc('activity_count')
            ->limit($limit)
            ->get();
    }

    public static function getActivityByDate($days = 30)
    {
        return static::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
