<?php

namespace App\Livewire\Pages;

use App\Models\Assignment;
use App\Models\TrainingModule;
use App\Models\User;
use App\Services\AssignmentService;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Assignment - RAIN LMS')]
class AssignmentPage extends Component
{
    public ?int $employeeId = null;

    public ?int $moduleId = null;

    public ?string $dueAt = null;

    public ?string $notes = null;

    public function assign(
        AssignmentService $service
    ): void {
        $manager =
            auth()->user();

        abort_unless(
            $manager->isManager()
            || $manager->isHr()
            || $manager->isSuperAdmin(),
            403
        );

        $this->validate([
            'employeeId' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'moduleId' => [
                'required',
                'integer',
                'exists:training_modules,id',
            ],

            'dueAt' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $employee =
            User::query()
                ->whereKey(
                    $this->employeeId
                )
                ->where(
                    'role',
                    User::ROLE_KARYAWAN
                )
                ->firstOrFail();

        $module =
            TrainingModule::query()
                ->whereKey(
                    $this->moduleId
                )
                ->where(
                    'is_active',
                    true
                )
                ->firstOrFail();

        $dueAt =
            $this->dueAt
                ? Carbon::parse(
                    $this->dueAt
                )
                : null;

        $service->assign(
            $manager,
            $employee,
            $module,
            $dueAt,
            $this->notes
        );

        $this->reset([
            'employeeId',
            'moduleId',
            'dueAt',
            'notes',
        ]);

        session()->flash(
            'success',
            'Assignment berhasil diberikan.'
        );
    }

    public function render()
    {
        $user =
            auth()->user();

        abort_unless(
            $user->isManager()
            || $user->isHr()
            || $user->isSuperAdmin(),
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
                ]);

        if (
            $user->isManager()
        ) {
            $employees->where(
                'department_id',
                $user->department_id
            );
        }

        $assignments =
            Assignment::query()
                ->with([
                    'user.department',
                    'trainingModule',
                ]);

        if (
            $user->isManager()
        ) {
            $assignments->where(
                'manager_id',
                $user->id
            );
        }

        return view(
            'livewire.pages.assignment-page',
            [
                'employees' => $employees
                    ->orderBy('name')
                    ->get(),

                'modules' => TrainingModule::query()
                    ->where(
                        'is_active',
                        true
                    )
                    ->where(
                        function ($query): void {
                            $query
                                ->whereNull(
                                    'published_at'
                                )
                                ->orWhere(
                                    'published_at',
                                    '<=',
                                    now()
                                );
                        }
                    )
                    ->orderBy('title')
                    ->get(),

                'assignments' => $assignments
                    ->latest()
                    ->limit(50)
                    ->get(),
            ]
        );
    }
}
