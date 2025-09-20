<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\Section::make('Información de la Categoría')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('cover_image')
                            ->label('Imagen de Portada')
                            ->height(200)
                            ->columnSpanFull(),
                        
                        \Filament\Infolists\Components\TextEntry::make('name')
                            ->label('Nombre')
                            ->size(\Filament\Support\Enums\FontSize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        
                        \Filament\Infolists\Components\TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable()
                            ->copyMessage('Slug copiado'),
                        
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'active' => 'Activo',
                                'inactive' => 'Inactivo',
                                default => $state,
                            }),
                        
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Creado')
                            ->dateTime('d/m/Y H:i:s'),
                        
                        \Filament\Infolists\Components\TextEntry::make('updated_at')
                            ->label('Actualizado')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2),
            ]);
    }
}
