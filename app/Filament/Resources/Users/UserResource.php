<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\ImageEntry::make('profile_image')
                    ->label('Foto de Perfil')
                    ->height(150)
                    ->circular()
                    ->placeholder('Sin foto'),
                \Filament\Infolists\Components\TextEntry::make('first_name')
                    ->label('Nombre')
                    ->size('lg')
                    ->weight('bold'),
                \Filament\Infolists\Components\TextEntry::make('last_name')
                    ->label('Apellido')
                    ->size('lg')
                    ->weight('bold'),
                \Filament\Infolists\Components\TextEntry::make('email')
                    ->label('Correo Electrónico')
                    ->copyable()
                    ->copyMessage('Email copiado'),
                \Filament\Infolists\Components\TextEntry::make('phone_number')
                    ->label('Teléfono')
                    ->copyable()
                    ->copyMessage('Teléfono copiado')
                    ->placeholder('No especificado'),
                \Filament\Infolists\Components\TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                        default => $state,
                    }),
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i:s')
                    ->placeholder('-'),
                \Filament\Infolists\Components\TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i:s')
                    ->placeholder('-'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Users\Pages\ManageUsers::route('/'),
        ];
    }
}
