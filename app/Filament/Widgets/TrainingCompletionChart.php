<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class TrainingCompletionChart extends ChartWidget
{
    /*
    |--------------------------------------------------------------------------
    | Heading
    |--------------------------------------------------------------------------
    */

    protected ?string $heading =
        'Status Penyelesaian Training';

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    protected ?string $description =
        'Distribusi seluruh enrollment berdasarkan status training.';

    /*
    |--------------------------------------------------------------------------
    | Position
    |--------------------------------------------------------------------------
    */

    protected static ?int $sort =
        2;

    /*
    |--------------------------------------------------------------------------
    | Width
    |--------------------------------------------------------------------------
    */

    protected int|string|array $columnSpan = [

            'md' => 1,

            'xl' => 2,

        ];

    /*
    |--------------------------------------------------------------------------
    | Height
    |--------------------------------------------------------------------------
    */

    protected ?string $maxHeight =
        '320px';

    /*
    |--------------------------------------------------------------------------
    | Polling
    |--------------------------------------------------------------------------
    */

    protected ?string $pollingInterval =
        '60s';

    /*
    |--------------------------------------------------------------------------
    | Chart Data
    |--------------------------------------------------------------------------
    */

    protected function getData(): array
    {
        $notStarted =
            Enrollment::query()
                ->where(
                    'status',
                    'not_started'
                )
                ->count();

        $inProgress =
            Enrollment::query()
                ->where(
                    'status',
                    'in_progress'
                )
                ->count();

        $completed =
            Enrollment::query()
                ->where(
                    'status',
                    'completed'
                )
                ->count();

        return [

            'datasets' => [

                [
                    'label' => 'Enrollment',

                    'data' => [

                        $notStarted,

                        $inProgress,

                        $completed,

                    ],

                    'backgroundColor' => [

                        '#94a3b8',

                        '#f59e0b',

                        '#22c55e',

                    ],

                    'borderColor' => [

                        '#64748b',

                        '#d97706',

                        '#16a34a',

                    ],

                    'borderWidth' => 1,
                ],

            ],

            'labels' => [

                'Belum Mulai',

                'Sedang Berjalan',

                'Selesai',

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Chart Type
    |--------------------------------------------------------------------------
    */

    protected function getType(): string
    {
        return 'doughnut';
    }
}
