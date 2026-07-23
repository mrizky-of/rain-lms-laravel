<?php

namespace App\Livewire\Pages;

use App\Models\Assignment;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\KpiLedger;
use App\Models\TrainingModule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard - RAIN LMS')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $totalModules =
            TrainingModule::query()
                ->visibleFor($user)
                ->count();

        $completedModules =
            Enrollment::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'status',
                    'completed'
                )
                ->count();

        $kpiScore =
            KpiLedger::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->sum('points');

        $certificates =
            Certificate::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->count();

        $activeEnrollments =
            Enrollment::query()
                ->with(
                    'trainingModule'
                )
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'status',
                    '!=',
                    'completed'
                )
                ->latest()
                ->limit(5)
                ->get();

        $deadlines =
            Assignment::query()
                ->with(
                    'trainingModule'
                )
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'status',
                    '!=',
                    'completed'
                )
                ->whereNotNull(
                    'due_at'
                )
                ->orderBy(
                    'due_at'
                )
                ->limit(5)
                ->get();

        return view(
            'livewire.pages.dashboard',
            [
                'totalModules' => $totalModules,

                'completedModules' => $completedModules,

                'kpiScore' => $kpiScore,

                'certificates' => $certificates,

                'activeEnrollments' => $activeEnrollments,

                'deadlines' => $deadlines,
            ]
        );
    }
}
