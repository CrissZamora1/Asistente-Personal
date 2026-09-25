<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capture extends Model
{
    protected $fillable = [
        'content',
        'type',
        'is_processed',
        'processed_into_type',
        'processed_into_id',
    ];

    protected $casts = [
        'is_processed' => 'boolean',
    ];

    public function scopeSinProcesar($query)
    {
        return $query->where('is_processed', false);
    }

    public function convertirEnTarea(array $extra = []): Task
    {
        $task = Task::create(array_merge([
            'title' => $this->content,
        ], $extra));

        $this->update([
            'is_processed' => true,
            'processed_into_type' => Task::class,
            'processed_into_id' => $task->id,
        ]);

        return $task;
    }
}
