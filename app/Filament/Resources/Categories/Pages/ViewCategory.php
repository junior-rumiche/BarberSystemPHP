<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected static ?string $title = 'Ver Categoría';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Editar'),
        ];
    }
}
