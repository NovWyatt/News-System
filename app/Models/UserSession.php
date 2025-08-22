<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_hash',
        'device_info',
        'ip_address',
        'user_agent',
        'is_active',
        'expires_at',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function revoke()
    {
        $this->update(['is_active' => false]);
    }
}
