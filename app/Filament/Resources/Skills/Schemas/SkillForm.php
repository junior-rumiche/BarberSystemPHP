<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de la Habilidad')
                    ->required()
                    ->maxLength(255)
                    ->autofocus()
                    ->columnSpanFull(),
            ]);
    }
}
