<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoalResource\Pages;
use App\Models\Goal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Metas';
    protected static ?string $modelLabel = 'Meta';
    protected static ?string $pluralModelLabel = 'Metas';
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

            Forms\Components\DatePicker::make('target_date')
                ->label('Fecha objetivo'),

            Forms\Components\TextInput::make('category')
                ->label('Categoría')
                ->placeholder('ej. gym, estudio, proyecto'),

            Forms\Components\Select::make('status')
                ->label('Estado')
                ->options([
                    'activa' => 'Activa',
                    'pausada' => 'Pausada',
                    'cumplida' => 'Cumplida',
                    'cancelada' => 'Cancelada',
                ])
                ->default('activa')
                ->required(),

            Forms\Components\TextInput::make('progress')
                ->label('Progreso (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0)
                ->suffix('%'),
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

                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge(),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->formatStateUsing(fn($state) => "{$state}%")
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'info' => 'activa',
                        'gray' => 'pausada',
                        'success' => 'cumplida',
                        'danger' => 'cancelada',
                    ])
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('target_date')
                    ->label('Fecha objetivo')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'pausada' => 'Pausada',
                        'cumplida' => 'Cumplida',
                        'cancelada' => 'Cancelada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('target_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoal::route('/create'),
            'edit' => Pages\EditGoal::route('/{record}/edit'),
        ];
    }
}
