<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Información de la Categoría')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                            ),
                        
                        \Filament\Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),
                        
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\FileUpload::make('cover_image')
                            ->label('Imagen de Portada')
                            ->image()
                            ->directory('categories')
                            ->visibility('public')
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\Toggle::make('status')
                            ->label('Estado Activo')
                            ->helperText('Activa o desactiva esta categoría')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->formatStateUsing(fn ($state) => $state === 'active' || $state === true)
                            ->dehydrateStateUsing(fn ($state) => $state ? 'active' : 'inactive'),
                    ])
                    ->columns(2),
            ]);
    }
}
