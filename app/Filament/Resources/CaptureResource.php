<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaptureResource\Pages;
use App\Models\Capture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CaptureResource extends Resource
{
    protected static ?string $model = Capture::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Captura rápida';
    protected static ?string $modelLabel = 'Captura';
    protected static ?string $pluralModelLabel = 'Capturas';
    protected static ?string $navigationGroup = 'Conocimiento';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('content')
                ->label('Contenido')
                ->required()
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\Select::make('type')
                ->label('Tipo')
                ->options([
                    'idea' => 'Idea',
                    'nota' => 'Nota',
                    'enlace' => 'Enlace',
                    'otro' => 'Otro',
                ])
                ->default('idea')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label('Contenido')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                Tables\Columns\IconColumn::make('is_processed')
                    ->label('Procesada')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_processed')
                    ->label('Procesadas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaptures::route('/'),
            'create' => Pages\CreateCapture::route('/create'),
            'edit' => Pages\EditCapture::route('/{record}/edit'),
        ];
    }
}
