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
        $pendientes = Reminder::where('remind_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('last_notified_at')
                    ->orWhereColumn('last_notified_at', '<', 'remind_at');
            })
            ->get();

        foreach ($pendientes as $reminder) {
            $notifier->enviar(
                mensaje: $reminder->note ?? $reminder->title,
                titulo: $reminder->title,
                prioridad: $reminder->is_critical ? 'urgent' : 'high'
            );

            $reminder->update(['last_notified_at' => now()]);

            // Si es recurrente, aquí después calculamos el siguiente remind_at
            // usando recurrence_rule. Por ahora, si no es recurrente, queda así.
        }

        $this->info("Recordatorios procesados: {$pendientes->count()}");
    }
}
