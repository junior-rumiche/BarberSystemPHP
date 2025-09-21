<?php

namespace App\Filament\Resources\Employees\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class EmployeeStatsChart extends ChartWidget
{
    protected ?string $heading = 'Empleados';

    protected ?string $pollingInterval = '30s';

    protected ?string $maxHeight = '300px';

    public ?string $filter = 'today';

    protected function getData(): array
    {
        $employees = Employee::whereYear('created_at', now()->year)->get();

        $data = $employees->groupBy(function ($employee) {
            return Carbon::parse($employee->created_at)->format('m');
        })->map(function ($group) {
            return $group->count();
        });

        $months = [
            '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
        ];

        $labels = [];
        $values = [];

        foreach ($months as $monthNumber => $monthName) {
            $labels[] = $monthName;
            $values[] = $data[$monthNumber] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Empleados Creados',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
