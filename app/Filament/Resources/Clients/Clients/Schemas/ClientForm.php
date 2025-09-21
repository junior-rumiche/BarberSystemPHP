<?php

namespace App\Filament\Resources\Clients\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Foto de Perfil')
                    ->image()
                    ->directory('clients')
                    ->visibility('public')
                    ->columnSpanFull(),
                TextInput::make('first_name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('last_name')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('phone_number')
                    ->label('Teléfono')
                    ->maxLength(20),
                \Filament\Forms\Components\Toggle::make('status')
                    ->label('Estado Activo')
                    ->helperText('Activa o desactiva este cliente')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->formatStateUsing(fn($state) => $state === 'active' || $state === true)
                    ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive'),
            ]);
    }
}
