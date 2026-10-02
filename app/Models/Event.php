<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_at',
        'end_at',
        'all_day',
        'color',
        'remind_minutes_before',
        'notified_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'all_day' => 'boolean',
        'notified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Si cambia la hora o el aviso, el evento vuelve a poder notificarse.
        static::updating(function (Event $event) {
            if ($event->isDirty(['start_at', 'remind_minutes_before'])) {
                $event->notified_at = null;
            }
        });
    }

    public function scopeProximos($query)
    {
        return $query->where('start_at', '>=', now())->orderBy('start_at');
    }

    public function scopePendientesDeAviso($query)
    {
        return $query->whereNull('notified_at')
            ->where('remind_minutes_before', '>', 0)
            ->where('start_at', '>=', now()->startOfDay())
            ->where('start_at', '<=', now()->addDay());
    }

    public function debeAvisarAhora(): bool
    {
        // Eventos de todo el día: aviso a las 8:00 de ese día.
        $avisarEn = $this->all_day
            ? $this->start_at->copy()->setTime(8, 0)
            : $this->start_at->copy()->subMinutes($this->remind_minutes_before);

        $limite = $this->all_day
            ? $this->start_at->copy()->endOfDay()
            : $this->start_at;

        return now()->between($avisarEn, $limite);
    }
}
