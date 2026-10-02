<?php

namespace App\Observers;

use App\Models\CommandLog;
use App\Services\NtfyNotifier;

class CommandLogObserver
{
    public function created(CommandLog $commandLog): void
    {
        if ($commandLog->status === 'pendiente') {
            app(NtfyNotifier::class)->enviar(
                mensaje: "Comando: {$commandLog->command}\nRiesgo: {$commandLog->risk_level}",
                titulo: '⚠️ Aprobación requerida',
                prioridad: 'urgent'
            );
        }
    }
}
