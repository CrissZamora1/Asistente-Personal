<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Agenda';
    protected static ?string $modelLabel = 'Evento';
    protected static ?string $pluralModelLabel = 'Eventos';
    protected static ?string $navigationGroup = 'Productividad';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Descripción')
                ->rows(3),

            Forms\Components\TextInput::make('location')
                ->label('Lugar'),

            Forms\Components\Toggle::make('all_day')
                ->label('Todo el día')
                ->reactive(),

            Forms\Components\DateTimePicker::make('start_at')
                ->label('Inicio')
                ->required()
                ->native(false),

            Forms\Components\DateTimePicker::make('end_at')
                ->label('Fin')
                ->native(false)
                ->visible(fn(Forms\Get $get) => ! $get('all_day')),

            Forms\Components\Select::make('remind_minutes_before')
                ->label('Avisar')
                ->options([
                    0 => 'No avisar',
                    5 => '5 minutos antes',
                    10 => '10 minutos antes',
                    30 => '30 minutos antes',
                    60 => '1 hora antes',
                    120 => '2 horas antes',
                    1440 => '1 día antes',
                ])
                ->default(30)
                ->required()
                ->helperText('Los eventos de todo el día avisan a las 8:00 de ese día.'),

            Forms\Components\ColorPicker::make('color')
                ->label('Color'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lugar'),

                Tables\Columns\IconColumn::make('all_day')
                    ->label('Todo el día')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('start_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
