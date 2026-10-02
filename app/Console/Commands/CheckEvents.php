<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\NtfyNotifier;
use Illuminate\Console\Command;

class CheckEvents extends Command
{
    protected $signature = 'events:check';
    protected $description = 'Avisa por ntfy de los eventos próximos';

    public function handle(NtfyNotifier $notifier): void
    {
        $avisados = 0;

        foreach (Event::pendientesDeAviso()->get() as $event) {
            if (! $event->debeAvisarAhora()) {
                continue;
            }

            $cuando = $event->all_day
                ? 'Hoy, todo el día'
                : $event->start_at->format('d/m/Y H:i');

            $mensaje = $cuando;

            if ($event->location) {
                $mensaje .= "\n📍 {$event->location}";
            }

            if ($event->description) {
                $mensaje .= "\n{$event->description}";
            }

            $enviado = $notifier->enviar(
                mensaje: $mensaje,
                titulo: "📅 {$event->title}",
                prioridad: 'high'
            );

            // Solo se marca como avisado si ntfy respondió bien;
            // si falló, se reintenta en el siguiente minuto.
            if ($enviado) {
                $event->update(['notified_at' => now()]);
                $avisados++;
            }
        }

        $this->info("Eventos avisados: {$avisados}");
    }
}
