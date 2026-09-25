<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'category',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completada',
            'completed_at' => now(),
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->whereNotIn('status', ['completada', 'cancelada']);
    }

    public function scopeVencidas($query)
    {
        return $query->pendientes()->whereDate('due_date', '<', now());
    }
}
