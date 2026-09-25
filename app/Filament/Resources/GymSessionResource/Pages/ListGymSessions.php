<?php

namespace App\Filament\Resources\GymSessionResource\Pages;

use App\Filament\Resources\GymSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymSessions extends ListRecords
{
    protected static string $resource = GymSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
