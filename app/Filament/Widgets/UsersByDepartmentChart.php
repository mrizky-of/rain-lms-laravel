<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class UsersByDepartmentChart extends ChartWidget
{
    /*
    |--------------------------------------------------------------------------
    | Heading
    |--------------------------------------------------------------------------
    */

    protected ?string $heading =
        'Karyawan per Department';

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    protected ?string $description =
        'Distribusi user aktif berdasarkan department.';

    /*
    |--------------------------------------------------------------------------
    | Position
    |--------------------------------------------------------------------------
    */

    protected static ?int $sort =
        3;

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
        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments =
            Department::query()

                ->where(
                    'is_active',
                    true
                )

                ->withCount([

                    'users' => function (
                        Builder $query
                    ): void {

                        $query

                            ->where(
                                'status',
                                User::STATUS_ACTIVE
                            )

                            ->where(
                                'role',
                                '!=',
                                User::ROLE_SUPER_ADMIN
                            );
                    },

                ])

                ->orderByDesc(
                    'users_count'
                )

                ->limit(
                    10
                )

                ->get();

        return [

            'datasets' => [

                [

                    'label' => 'Jumlah User',

                    'data' => $departments
                        ->pluck(
                            'users_count'
                        )
                        ->values()
                        ->all(),

                    'backgroundColor' => '#22c55e',

                    'borderColor' => '#16a34a',

                    'borderWidth' => 1,

                ],

            ],

            'labels' => $departments
                ->pluck(
                    'name'
                )
                ->values()
                ->all(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Chart Type
    |--------------------------------------------------------------------------
    */

    protected function getType(): string
    {
        return 'bar';
    }
}
