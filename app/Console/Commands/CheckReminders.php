<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Services\NtfyNotifier;
use Illuminate\Console\Command;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check';
    protected $description = 'Revisa recordatorios pendientes y envía notificación ntfy';

    public function handle(NtfyNotifier $notifier): void
    {
        $pendientes = Reminder::pendientesDeNotificar()->get();

        foreach ($pendientes as $reminder) {
            $enviado = $notifier->enviar(
                mensaje: $reminder->note ?? $reminder->title,
                titulo: $reminder->title,
                prioridad: $reminder->is_critical ? 'urgent' : 'high'
            );

            // Solo se marca como notificado si ntfy realmente respondió bien;
            // si falló, se reintenta en el siguiente minuto.
            if ($enviado) {
                $reminder->update(['last_notified_at' => now()]);
            }

            // Si es recurrente, aquí después calculamos el siguiente remind_at
            // usando recurrence_rule.
        }

        $this->info("Recordatorios procesados: {$pendientes->count()}");
    }
}
