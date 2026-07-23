<?php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\TrainingModule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview
    extends StatsOverviewWidget
{
    /*
    |--------------------------------------------------------------------------
    | Position
    |--------------------------------------------------------------------------
    */

    protected static ?int $sort =
        1;

    /*
    |--------------------------------------------------------------------------
    | Width
    |--------------------------------------------------------------------------
    */

    protected int|string|array
        $columnSpan =
            'full';

    /*
    |--------------------------------------------------------------------------
    | Polling
    |--------------------------------------------------------------------------
    |
    | Tidak perlu refresh setiap 5 detik.
    |
    */

    protected ?string
        $pollingInterval =
            '30s';

    /*
    |--------------------------------------------------------------------------
    | Stats
    |--------------------------------------------------------------------------
    */

    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Total Karyawan
        |--------------------------------------------------------------------------
        */

        $totalEmployees =
            User::query()
                ->where(
                    'role',
                    User::ROLE_KARYAWAN
                )
                ->where(
                    'status',
                    User::STATUS_ACTIVE
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Active Training Modules
        |--------------------------------------------------------------------------
        */

        $activeModules =
            TrainingModule::query()
                ->where(
                    'is_active',
                    true
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Completed Enrollments
        |--------------------------------------------------------------------------
        */

        $completedTrainings =
            Enrollment::query()
                ->where(
                    'status',
                    'completed'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Active Assignments
        |--------------------------------------------------------------------------
        */

        $activeAssignments =
            Assignment::query()
                ->where(
                    'status',
                    '!=',
                    'completed'
                )
                ->count();

        return [

            /*
            |--------------------------------------------------------------------------
            | Total Employee
            |--------------------------------------------------------------------------
            */

            Stat::make(
                'Total Karyawan',
                number_format(
                    $totalEmployees
                )
            )
                ->description(
                    'Karyawan aktif di portal LMS'
                )
                ->color(
                    'primary'
                ),

            /*
            |--------------------------------------------------------------------------
            | Active Modules
            |--------------------------------------------------------------------------
            */

            Stat::make(
                'Modul Aktif',
                number_format(
                    $activeModules
                )
            )
                ->description(
                    'Training module tersedia'
                )
                ->color(
                    'info'
                ),

            /*
            |--------------------------------------------------------------------------
            | Completed Training
            |--------------------------------------------------------------------------
            */

            Stat::make(
                'Training Selesai',
                number_format(
                    $completedTrainings
                )
            )
                ->description(
                    'Total enrollment completed'
                )
                ->color(
                    'success'
                ),

            /*
            |--------------------------------------------------------------------------
            | Active Assignment
            |--------------------------------------------------------------------------
            */

            Stat::make(
                'Assignment Aktif',
                number_format(
                    $activeAssignments
                )
            )
                ->description(
                    'Assignment belum selesai'
                )
                ->color(
                    'warning'
                ),

        ];
    }
}