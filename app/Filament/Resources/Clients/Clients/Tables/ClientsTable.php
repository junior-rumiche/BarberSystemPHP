<?php

namespace App\Filament\Resources\Clients\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                TextColumn::make('first_name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Apellido')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Teléfono'),
                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'active' => 'success', // verde
                        'inactive' => 'danger', // rojo
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make()
                        ->label('Ver')
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    EditAction::make()
                        ->label('Editar')
                        ->icon('heroicon-o-pencil')
                        ->color('warning'),

                    \Filament\Actions\Action::make('deactivate')
                        ->label('Desactivar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($record) {
                            $record->update(['status' => 'inactive']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Cliente')
                        ->modalDescription('¿Estás seguro de que quieres desactivar este cliente? Esto lo marcará como inactivo.')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn($record) => $record->status !== 'inactive'),
                    \Filament\Actions\Action::make('activate')
                        ->label('Activar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($record) {
                            $record->update(['status' => 'active']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Activar Cliente')
                        ->modalDescription('¿Estás seguro de que quieres activar este cliente?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn($record) => $record->status === 'inactive'),
                    \Filament\Actions\DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Cliente')
                        ->modalDescription('¿Estás seguro de que quieres eliminar este cliente? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar'),
                ])
                    ->label('Acciones')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('bulk_activate')
                        ->label('Activar Seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['status' => 'active']))
                        ->requiresConfirmation()
                        ->modalHeading('Activar Clientes')
                        ->modalDescription('¿Estás seguro de que quieres activar los clientes seleccionados?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar'),
                    \Filament\Actions\BulkAction::make('bulk_deactivate')
                        ->label('Desactivar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['status' => 'inactive']))
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Clientes')
                        ->modalDescription('¿Estás seguro de que quieres desactivar los clientes seleccionados?')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar'),
                    DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Clientes')
                        ->modalDescription('¿Estás seguro de que quieres eliminar los clientes seleccionados? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar'),
                ]),
            ]);
    }
}
