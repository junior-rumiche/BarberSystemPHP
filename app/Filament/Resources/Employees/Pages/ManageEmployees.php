<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployees extends ManageRecords
{
    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Empleados';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Empleado')
                ->createAnother(false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\EmployeeStatsOverview::class,
        ];
    }
}