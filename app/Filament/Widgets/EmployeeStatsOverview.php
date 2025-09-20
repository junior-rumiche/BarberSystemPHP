<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Employee::count();
        $active = Employee::where('status', 'active')->count();
        $inactive = Employee::where('status', 'inactive')->count();

        return [
            Stat::make('Total de Empleados', $total)
                ->description('Todos los empleados registrados')
                ->color('primary'),
            Stat::make('Empleados Activos', $active)
                ->description('Empleados con estado activo')
                ->color('success'),
            Stat::make('Empleados Inactivos', $inactive)
                ->description('Empleados con estado inactivo')
                ->color('danger'),
        ];
    }
}
