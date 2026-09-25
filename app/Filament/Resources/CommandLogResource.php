<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandLogResource\Pages;
use App\Models\CommandLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommandLogResource extends Resource
{
    protected static ?string $model = CommandLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Consola de comandos';
    protected static ?string $modelLabel = 'Comando';
    protected static ?string $pluralModelLabel = 'Comandos';
    protected static ?string $navigationGroup = 'Sistema';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('command')
                ->label('Comando')
                ->required()
                ->rows(2)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('working_directory')
                ->label('Carpeta de trabajo'),

            Forms\Components\Select::make('risk_level')
                ->label('Nivel de riesgo')
                ->options([
                    'bajo' => 'Bajo',
                    'medio' => 'Medio',
                    'alto' => 'Alto',
                ])
                ->default('bajo')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('command')
                    ->label('Comando')
                    ->limit(50)
                    ->fontFamily('mono'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'pendiente',
                        'info' => 'aprobado',
                        'danger' => 'rechazado',
                        'success' => 'ejecutado',
                        'warning' => 'fallido',
                    ])
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                Tables\Columns\BadgeColumn::make('risk_level')
                    ->label('Riesgo')
                    ->colors([
                        'success' => 'bajo',
                        'warning' => 'medio',
                        'danger' => 'alto',
                    ])
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Solicitado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                        'ejecutado' => 'Ejecutado',
                        'fallido' => 'Fallido',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(CommandLog $record) => $record->status === 'pendiente')
                    ->requiresConfirmation(fn(CommandLog $record) => $record->risk_level === 'alto')
                    ->modalHeading('⚠️ Comando de riesgo ALTO')
                    ->modalDescription(fn(CommandLog $record) => "Vas a aprobar: {$record->command}")
                    ->action(fn(CommandLog $record) => $record->aprobar()),

                Tables\Actions\Action::make('ejecutar')
                    ->label('Ejecutar')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->visible(fn(CommandLog $record) => $record->status === 'aprobado')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar ejecución')
                    ->modalDescription(fn(CommandLog $record) => "Se va a correr: {$record->command}")
                    ->action(function (CommandLog $record) {
                        app(\App\Services\CommandRunner::class)->ejecutar($record);
                    }),

                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn(CommandLog $record) => $record->status === 'pendiente')
                    ->action(fn(CommandLog $record) => $record->rechazar()),

                Tables\Actions\ViewAction::make()->label('Ver'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommandLogs::route('/'),
            'create' => Pages\CreateCommandLog::route('/create'),
            'edit' => Pages\EditCommandLog::route('/{record}/edit'),
        ];
    }
}
