<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    protected $fillable = [
        'command',
        'working_directory',
        'status',
        'risk_level',
        'output',
        'exit_code',
        'approved_at',
        'executed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'executed_at' => 'datetime',
    ];

    public function aprobar(): void
    {
        $this->update([
            'status' => 'aprobado',
            'approved_at' => now(),
        ]);
    }

    public function rechazar(): void
    {
        $this->update(['status' => 'rechazado']);
    }

    public function registrarResultado(int $exitCode, string $output): void
    {
        $this->update([
            'status' => $exitCode === 0 ? 'ejecutado' : 'fallido',
            'exit_code' => $exitCode,
            'output' => $output,
            'executed_at' => now(),
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->where('status', 'pendiente');
    }
}
