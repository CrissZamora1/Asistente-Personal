<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymExercise extends Model
{
    protected $fillable = [
        'gym_session_id',
        'name',
        'sets',
        'reps',
        'weight',
        'notes',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(GymSession::class, 'gym_session_id');
    }
}
