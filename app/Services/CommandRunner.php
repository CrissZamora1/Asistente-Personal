<?php

namespace App\Services;

use App\Models\CommandLog;
use Illuminate\Support\Facades\Process;
use InvalidArgumentException;

class CommandRunner
{
    /**
     * Valida el comando contra la lista blanca y crea el registro
     * en command_logs con estado "pendiente" (o "aprobado" si es
     * de bajo riesgo, para agilizar).
     */
    public function solicitar(string $command, ?string $workingDirectory = null): CommandLog
    {
        $this->validarComandoPermitido($command);
        $this->validarCarpetaPermitida($workingDirectory);

        $riesgo = $this->detectarRiesgo($command);

        return CommandLog::create([
            'command' => $command,
            'working_directory' => $workingDirectory,
            'risk_level' => $riesgo,
            'status' => 'pendiente', // siempre pendiente, aprobación manual desde el panel
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
        $primerToken = strtok(trim($command), ' ');

        if (! in_array($primerToken, config('comandos.permitidos'), true)) {
            throw new InvalidArgumentException("El comando '{$primerToken}' no está en la lista blanca.");
        }
    }

    protected function validarCarpetaPermitida(?string $workingDirectory): void
    {
        if (! $workingDirectory) {
            return;
        }

        foreach (config('comandos.carpetas_permitidas') as $permitida) {
            if (str_starts_with($workingDirectory, $permitida)) {
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
