<?php

namespace App\Filament\Resources\Clients\Clients\Pages;

use App\Filament\Resources\Clients\Clients\ClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;
    protected static ?string $title = 'Clientes';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('Agregar Cliente')
                ->modalSubmitActionLabel('Guardar')
                ->modalCancelActionLabel('Cancelar')
                ->modal(),
        ];
    }
}
