<?php

namespace App\Livewire\Pages;

use App\Models\ManagerReview;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Review Atasan - RAIN LMS')]
class ManagerReviewPage extends Component
{
    public array $scores = [];

    public array $statuses = [];

    public array $notes = [];

    public function saveReview(
        int $employeeId
    ): void {
        $manager =
            auth()->user();

        abort_unless(
            $manager->isManager()
            || $manager->isHr()
            || $manager->isSuperAdmin(),
            403
        );

        $employee =
            User::query()
                ->where(
                    'role',
                    User::ROLE_KARYAWAN
                )
                ->findOrFail(
                    $employeeId
                );

        if (
            $manager->isManager()
            && (int) $employee->department_id
                !== (int) $manager->department_id
        ) {
            abort(403);
        }

        $this->validate([
            "scores.{$employeeId}" => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            "statuses.{$employeeId}" => [
                'nullable',
                'in:lulus,proses,review,perhatian',
            ],

            "notes.{$employeeId}" => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        ManagerReview::updateOrCreate(
            [
                'manager_id' => $manager->id,

                'user_id' => $employee->id,

                'period' => now()->format('Y-m'),
            ],
            [
                'score' => $this->scores[
                        $employeeId
                    ] ?? null,

                'status' => $this->statuses[
                        $employeeId
                    ] ?? 'review',

                'notes' => $this->notes[
                        $employeeId
                    ] ?? null,

                'reviewed_at' => now(),
            ]
        );

        unset(
            $this->scores[
                $employeeId
            ],
            $this->statuses[
                $employeeId
            ],
            $this->notes[
                $employeeId
            ]
        );

        session()->flash(
            'success',
            'Review berhasil disimpan.'
        );
    }

    public function render()
    {
        $manager =
            auth()->user();

        abort_unless(
            $manager->isManager()
            || $manager->isHr()
            || $manager->isSuperAdmin(),
            403
        );

        $employees =
            User::query()
                ->where(
                    'role',
                    User::ROLE_KARYAWAN
                )
                ->where(
                    'status',
                    User::STATUS_ACTIVE
                )
                ->with([
                    'department',
                    'position',
                    'enrollments.trainingModule',
                    'kpiLedgers',
                ]);

        if (
            $manager->isManager()
        ) {
            $employees->where(
                'department_id',
                $manager->department_id
            );
        }

        return view(
            'livewire.pages.manager-review-page',
            [
                'employees' => $employees
                    ->orderBy('name')
                    ->get(),
            ]
        );
    }
}
