<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymSessionResource\Pages;
use App\Models\GymSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GymSessionResource extends Resource
{
    protected static ?string $model = GymSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'Sesiones de gym';
    protected static ?string $modelLabel = 'Sesión';
    protected static ?string $pluralModelLabel = 'Sesiones de gym';
    protected static ?string $navigationGroup = 'Gimnasio';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('session_date')
                ->label('Fecha')
                ->required()
                ->default(now()),

            Forms\Components\Select::make('type')
                ->label('Tipo')
                ->options([
                    'pesas' => 'Pesas',
                    'resistencia' => 'Resistencia',
                    'boxeo' => 'Boxeo',
                    'mixto' => 'Mixto',
                ])
                ->default('pesas')
                ->required(),

            Forms\Components\TextInput::make('duration_minutes')
                ->label('Duración (minutos)')
                ->numeric(),

            Forms\Components\TextInput::make('body_weight')
                ->label('Peso corporal')
                ->numeric()
                ->step(0.1)
                ->suffix('kg'),

            Forms\Components\Textarea::make('notes')
                ->label('Notas')
                ->rows(2),

            Forms\Components\Repeater::make('exercises')
                ->relationship()
                ->label('Ejercicios')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Ejercicio')
                        ->required(),
                    Forms\Components\TextInput::make('sets')
                        ->label('Series')
                        ->numeric(),
                    Forms\Components\TextInput::make('reps')
                        ->label('Repeticiones')
                        ->numeric(),
                    Forms\Components\TextInput::make('weight')
                        ->label('Peso')
                        ->numeric()
                        ->step(0.5),
                ])
                ->columns(4)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duración')
                    ->suffix(' min'),

                Tables\Columns\TextColumn::make('exercises_count')
                    ->label('Ejercicios')
                    ->counts('exercises'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('session_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGymSessions::route('/'),
            'create' => Pages\CreateGymSession::route('/create'),
            'edit' => Pages\EditGymSession::route('/{record}/edit'),
        ];
    }
}
