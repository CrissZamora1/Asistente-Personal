<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NtfyNotifier
{
    public function enviar(string $mensaje, ?string $titulo = null, string $prioridad = 'urgent', array $opciones = []): bool
    {
        $url = rtrim(config('services.ntfy.url'), '/') . '/' . config('services.ntfy.topic');

        $headers = array_merge([
            'Priority' => $prioridad,
        ], $opciones);

        if ($titulo) {
            $headers['Title'] = $titulo;
        }

        $response = Http::withHeaders($headers)->withBody($mensaje, 'text/plain')->post($url);

        return $response->successful();
    }
}
