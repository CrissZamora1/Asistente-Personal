<?php

namespace App\Services;

use App\Models\CommandLog;
use Illuminate\Support\Facades\Process;
use InvalidArgumentException;

class CommandRunner
{
    /**
     * Valida el comando contra la lista blanca y crea el registro
     * en command_logs con estado "pendiente". La aprobación es siempre
     * manual desde el panel.
     */
    public function solicitar(string $command, ?string $workingDirectory = null): CommandLog
    {
        $this->validarComandoPermitido($command);
        $this->validarCarpetaPermitida($workingDirectory);

        return CommandLog::create([
            'command' => trim($command),
            'working_directory' => $workingDirectory,
            'risk_level' => $this->detectarRiesgo($command),
            'status' => 'pendiente',
        ]);
    }

    /**
     * Ejecuta un comando ya aprobado. Solo se debe llamar
     * cuando $log->status === 'aprobado'.
     */
    public function ejecutar(CommandLog $log): CommandLog
    {
        if ($log->status !== 'aprobado') {
            throw new InvalidArgumentException('El comando no está aprobado todavía.');
        }

        // Se revalida por si el registro se creó o editó sin pasar por solicitar().
        $this->validarComandoPermitido($log->command);
        $this->validarCarpetaPermitida($log->working_directory);

        $resultado = Process::path($log->working_directory ?? base_path())
            ->timeout(120)
            ->run($log->command);

        $log->registrarResultado(
            $resultado->exitCode() ?? 1,
            $resultado->output() . PHP_EOL . $resultado->errorOutput()
        );

        return $log->fresh();
    }

    protected function validarComandoPermitido(string $command): void
    {
        $command = trim($command);

        if ($command === '') {
            throw new InvalidArgumentException('El comando está vacío.');
        }

        // Bloquea encadenado (&, |, ;), redirecciones, sustituciones y saltos de línea.
        if (preg_match('/[&|;<>`$^%()\r\n]/', $command)) {
            throw new InvalidArgumentException('El comando contiene caracteres no permitidos (encadenado o redirección).');
        }

        $primerToken = strtolower((string) strtok($command, " \t"));

        if (! in_array($primerToken, config('comandos.permitidos'), true)) {
            throw new InvalidArgumentException("El comando '{$primerToken}' no está en la lista blanca.");
        }

        foreach (config('comandos.patrones_prohibidos', []) as $patron) {
            if (stripos($command, $patron) !== false) {
                throw new InvalidArgumentException("El comando contiene un patrón prohibido: '{$patron}'.");
            }
        }
    }

    protected function validarCarpetaPermitida(?string $workingDirectory): void
    {
        if (! $workingDirectory) {
            return;
        }

        $real = realpath($workingDirectory);

        if ($real === false) {
            throw new InvalidArgumentException("La carpeta '{$workingDirectory}' no existe.");
        }

        $real = rtrim($real, '\\/') . DIRECTORY_SEPARATOR;

        foreach (config('comandos.carpetas_permitidas') as $permitida) {
            $base = realpath($permitida);

            if ($base === false) {
                continue;
            }

            $base = rtrim($base, '\\/') . DIRECTORY_SEPARATOR;

            if (stripos($real, $base) === 0) {
                return;
            }
        }

        throw new InvalidArgumentException("La carpeta '{$workingDirectory}' no está permitida.");
    }

    protected function detectarRiesgo(string $command): string
    {
        foreach (config('comandos.palabras_riesgo_alto') as $palabra) {
            if (stripos($command, $palabra) !== false) {
                return 'alto';
            }
        }

        return 'bajo';
    }
}
