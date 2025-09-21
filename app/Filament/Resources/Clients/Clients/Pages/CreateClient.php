<?php

namespace App\Filament\Resources\Clients\Clients\Pages;

use App\Filament\Resources\Clients\Clients\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
    protected static ?string $title = 'Crear Cliente';

     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Cliente creado exitosamente';
    }
}
