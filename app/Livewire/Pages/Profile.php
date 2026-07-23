<?php

namespace App\Livewire\Pages;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\KpiLedger;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Profil Saya - RAIN LMS')]
class Profile extends Component
{
    public function render()
    {
        $user =
            auth()
                ->user()
                ->load([
                    'department',
                    'position',
                ]);

        $kpi =
            KpiLedger::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->sum('points');

        $completed =
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

        $certificates =
            Certificate::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->with(
                    'trainingModule'
                )
                ->latest(
                    'issued_at'
                )
                ->get();

        return view(
            'livewire.pages.profile',
            [
                'user' => $user,

                'kpi' => $kpi,

                'completed' => $completed,

                'certificates' => $certificates,
            ]
        );
    }
}
