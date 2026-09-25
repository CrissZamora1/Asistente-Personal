<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReminderResource\Pages;
use App\Models\Reminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationLabel = 'Recordatorios';
    protected static ?string $modelLabel = 'Recordatorio';
    protected static ?string $pluralModelLabel = 'Recordatorios';
    protected static ?string $navigationGroup = 'Productividad';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('note')
                ->label('Nota')
                ->rows(3),

            Forms\Components\DateTimePicker::make('remind_at')
                ->label('Fecha y hora')
                ->required()
                ->native(false),

            Forms\Components\Toggle::make('is_recurring')
                ->label('¿Es recurrente?')
                ->reactive(),

            Forms\Components\TextInput::make('recurrence_rule')
                ->label('Regla de repetición')
                ->placeholder('ej. daily, weekly:lun,mie')
                ->visible(fn(Forms\Get $get) => $get('is_recurring')),

            Forms\Components\Toggle::make('is_critical')
                ->label('¿Es crítico?')
                ->helperText('Usa alarma nativa, no solo notificación push'),
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

                Tables\Columns\TextColumn::make('remind_at')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Recurrente')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_critical')
                    ->label('Crítico')
                    ->boolean()
                    ->trueColor('danger'),

                Tables\Columns\TextColumn::make('last_notified_at')
                    ->label('Última notificación')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin notificar')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_critical')
                    ->label('Críticos'),
                Tables\Filters\TernaryFilter::make('is_recurring')
                    ->label('Recurrentes'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('remind_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }
}
