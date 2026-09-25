<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymSession extends Model
{
    protected $fillable = [
        'session_date',
        'type',
        'duration_minutes',
        'notes',
        'body_weight',
    ];

    protected $casts = [
        'session_date' => 'date',
        'body_weight' => 'decimal:2',
    ];

    public function exercises(): HasMany
    {
        return $this->hasMany(GymExercise::class);
    }
}
