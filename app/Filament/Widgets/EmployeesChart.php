<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EmployeesChart extends ChartWidget
{
    protected static ?string $heading = 'Estado de Empleados';

    protected function getData(): array
    {
        $data = Employee::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Empleados',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#36A2EB', // active
                        '#FF6384', // inactive
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
