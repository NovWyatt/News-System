<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'user_roles';

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

