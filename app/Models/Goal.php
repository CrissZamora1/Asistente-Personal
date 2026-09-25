<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_date',
        'progress',
        'status',
        'category',
    ];

    protected $casts = [
        'target_date' => 'date',
        'progress' => 'integer',
    ];

    public function scopeActivas($query)
    {
        return $query->where('status', 'activa');
    }
}
