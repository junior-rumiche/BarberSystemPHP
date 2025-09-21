<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'Usuarios';

     protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Categoría')
                ->createAnother(false),
        ];
    }

}
