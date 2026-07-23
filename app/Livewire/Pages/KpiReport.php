<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Laporan KPI - RAIN LMS')]
class KpiReport extends Component
{
    public function render()
    {
        $currentUser =
            auth()->user();

        abort_unless(
            $currentUser->isManager()
            || $currentUser->isHr()
            || $currentUser->isSuperAdmin(),
            403
        );

        $query =
            User::query()
                ->where(
                    'role',
                    User::ROLE_KARYAWAN
                )
                ->with([
                    'department',
                    'position',
                ])
                ->withSum(
                    'kpiLedgers as kpi_total',
                    'points'
                )
                ->withCount([
                    'enrollments as total_modules',

                    'enrollments as completed_modules' => fn ($query) => $query->where(
                        'status',
                        'completed'
                    ),
                ])
                ->orderBy('name');

        if (
            $currentUser->isManager()
        ) {
            $query->where(
                'department_id',
                $currentUser->department_id
            );
        }

        $users =
            $query->get();

        $averageCompletion =
            $users->isNotEmpty()
                ? round(
                    $users->avg(
                        function (
                            User $user
                        ): float {
                            if (
                                $user->total_modules
                                < 1
                            ) {
                                return 0;
                            }

                            return (
                                $user->completed_modules
                                / $user->total_modules
                            ) * 100;
                        }
                    ),
                    2
                )
                : 0;

        $averageKpi =
            $users->isNotEmpty()
                ? round(
                    (float)
                    $users->avg(
                        fn (User $user) => (float)
                            ($user->kpi_total ?? 0)
                    ),
                    2
                )
                : 0;

        return view(
            'livewire.pages.kpi-report',
            [
                'users' => $users,

                'averageCompletion' => $averageCompletion,

                'averageKpi' => $averageKpi,
            ]
        );
    }
}
