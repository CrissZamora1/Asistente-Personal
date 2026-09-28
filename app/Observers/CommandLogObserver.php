<?php

namespace App\Observers;

use App\Models\CommandLog;

class CommandLogObserver
{
    /**
     * Handle the CommandLog "created" event.
     */
    public function created(CommandLog $commandLog): void
    {
        if ($commandLog->status === 'pendiente') {
            app(\App\Services\NtfyNotifier::class)->enviar(
                mensaje: "Comando: {$commandLog->command}\nRiesgo: {$commandLog->risk_level}",
                titulo: '⚠️ Aprobación requerida',
                prioridad: 'urgent'
            );
        }
    }

    /**
     * Handle the CommandLog "updated" event.
     */
    public function updated(CommandLog $commandLog): void
    {
        //
    }

    /**
     * Handle the CommandLog "deleted" event.
     */
    public function deleted(CommandLog $commandLog): void
    {
        //
    }

    /**
     * Handle the CommandLog "restored" event.
     */
    public function restored(CommandLog $commandLog): void
    {
        //
    }

    /**
     * Handle the CommandLog "force deleted" event.
     */
    public function forceDeleted(CommandLog $commandLog): void
    {
        //
    }
}
