<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Service::count();
        $active = Service::where('status', 'active')->count();
        $inactive = Service::where('status', 'inactive')->count();

        return [
            Stat::make('Total de Servicios', $total)
                ->description('Todos los servicios registrados')
                ->color('primary'),
            Stat::make('Servicios Activos', $active)
                ->description('Servicios con estado activo')
                ->color('success'),
            Stat::make('Servicios Inactivos', $inactive)
                ->description('Servicios con estado inactivo')
                ->color('danger'),
        ];
    }
}
