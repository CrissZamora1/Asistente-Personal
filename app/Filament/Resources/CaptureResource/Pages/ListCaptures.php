<?php

namespace App\Filament\Resources\CaptureResource\Pages;

use App\Filament\Resources\CaptureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaptures extends ListRecords
{
    protected static string $resource = CaptureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
