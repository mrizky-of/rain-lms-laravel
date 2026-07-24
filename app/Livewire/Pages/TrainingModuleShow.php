<?php

namespace App\Livewire\Pages;

use App\Models\Enrollment;
use App\Models\TrainingModule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Training - RAIN LMS')]
class TrainingModuleShow extends Component
{
    public TrainingModule $module;

    public Enrollment $enrollment;

    public function mount(
        TrainingModule $module
    ): void {
        $allowed =
            TrainingModule::query()
                ->visibleFor(
                    auth()->user()
                )
                ->whereKey(
                    $module->id
                )
                ->exists();

        abort_unless(
            $allowed,
            403
        );

        $this->module =
            $module;

        $this->enrollment =
            Enrollment::firstOrCreate(
                [
                    'user_id' => auth()->id(),

                    'training_module_id' => $module->id,
                ],
                [
                    'source' => 'self',

                    'status' => 'in_progress',

                    'progress_percentage' => 25,

                    'started_at' => now(),
                ]
            );

        if (
            $this->enrollment
                ->status
            === 'not_started'
        ) {
            $this->enrollment
                ->update([
                    'status' => 'in_progress',

                    'progress_percentage' => max(
                        25,
                        (float)
                        $this
                            ->enrollment
                            ->progress_percentage
                    ),

                    'started_at' => $this
                        ->enrollment
                        ->started_at
                        ?? now(),
                ]);
        }

        if (
            $this->enrollment
                ->status
            !== 'completed'
            &&
            (float)
            $this->enrollment
                ->progress_percentage
            < 25
        ) {
            $this->enrollment
                ->update([
                    'progress_percentage' => 25,
                ]);
        }

        $this->enrollment
            ->refresh();
    }

    public function completeMaterial(): void
    {
        if (
            $this->enrollment
                ->status
            === 'completed'
        ) {
            return;
        }

        $this->enrollment
            ->update([
                'status' => 'in_progress',

                'progress_percentage' => 70,

                'material_completed_at' => now(),
            ]);

        $this->enrollment
            ->refresh();

        session()->flash(
            'success',
            'Materi berhasil ditandai selesai. Silakan lanjutkan ke test.'
        );
    }

    public function render()
    {
        $this->module->load([
            'quizzes' => fn ($query) => $query->where(
                'is_active',
                true
            ),
        ]);

        return view(
            'livewire.pages.training-module-show'
        );
    }
}
