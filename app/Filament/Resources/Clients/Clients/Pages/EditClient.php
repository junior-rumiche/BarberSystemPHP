<?php

namespace App\Filament\Resources\Clients\Clients\Pages;

use App\Filament\Resources\Clients\Clients\ClientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Eliminar Cliente')
                ->modalSubmitActionLabel('Sí, eliminar')
                ->modalCancelActionLabel('Cancelar'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\EditAction::make()
                ->modalHeading('Editar Cliente')
                ->modalSubmitActionLabel('Guardar cambios')
                ->modalCancelActionLabel('Cancelar')
                ->slideOver(),
        ];
    }
}
