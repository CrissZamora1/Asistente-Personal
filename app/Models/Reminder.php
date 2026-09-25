<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'title',
        'note',
        'remind_at',
        'is_recurring',
        'recurrence_rule',
        'last_notified_at',
        'is_critical',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'last_notified_at' => 'datetime',
        'is_recurring' => 'boolean',
        'is_critical' => 'boolean',
    ];

    public function scopePendientesDeNotificar($query)
    {
        return $query->where('remind_at', '<=', now())
            ->whereNull('last_notified_at');
    }
}
