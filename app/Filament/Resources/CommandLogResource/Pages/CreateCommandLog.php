<?php

namespace App\Filament\Resources\CommandLogResource\Pages;

use App\Filament\Resources\CommandLogResource;
use App\Services\CommandRunner;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CreateCommandLog extends CreateRecord
{
    protected static string $resource = CommandLogResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CommandRunner::class)->solicitar(
                $data['command'],
                $data['working_directory'] ?? null
            );
        } catch (InvalidArgumentException $e) {
            Notification::make()
                ->title('Comando rechazado')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
