<?php

namespace App\Livewire\Pages;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\TrainingModule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Training - RAIN LMS')]
class TrainingModuleShow extends Component
{
    public TrainingModule $module;

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

        $this->module = $module;

        $enrollment =
            Enrollment::firstOrCreate(
                [
                    'user_id' => auth()->id(),

                    'training_module_id' => $module->id,
                ],
                [
                    'source' => 'self',

                    'status' => 'in_progress',

                    'started_at' => now(),
                ]
            );

        if (
            $enrollment->status
            === 'not_started'
        ) {
            $enrollment->update([
                'status' => 'in_progress',

                'started_at' => $enrollment->started_at
                        ?? now(),
            ]);
        }
    }

    public function completeLesson(
        int $lessonId
    ): void {
        $lesson =
            Lesson::query()
                ->where(
                    'training_module_id',
                    $this->module->id
                )
                ->where(
                    'is_active',
                    true
                )
                ->findOrFail(
                    $lessonId
                );

        LessonProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),

                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,

                'completed_at' => now(),
            ]
        );

        $totalLessons =
            $this->module
                ->lessons()
                ->where(
                    'is_active',
                    true
                )
                ->count();

        $completedLessons =
            LessonProgress::query()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->where(
                    'is_completed',
                    true
                )
                ->whereHas(
                    'lesson',
                    function ($query): void {
                        $query
                            ->where(
                                'training_module_id',
                                $this->module->id
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }
                )
                ->count();

        $percentage =
            $totalLessons > 0
                ? round(
                    (
                        $completedLessons
                        / $totalLessons
                    ) * 100,
                    2
                )
                : 0;

        Enrollment::query()
            ->where(
                'user_id',
                auth()->id()
            )
            ->where(
                'training_module_id',
                $this->module->id
            )
            ->update([
                'status' => 'in_progress',

                'progress_percentage' => $percentage,
            ]);
    }

    public function render()
    {
        $this->module->load([
            'lessons' => fn ($query) => $query->where(
                'is_active',
                true
            ),

            'quizzes' => fn ($query) => $query->where(
                'is_active',
                true
            ),
        ]);

        $completedLessonIds =
            LessonProgress::query()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->where(
                    'is_completed',
                    true
                )
                ->whereHas(
                    'lesson',
                    fn ($query) => $query->where(
                        'training_module_id',
                        $this->module->id
                    )
                )
                ->pluck(
                    'lesson_id'
                )
                ->all();

        return view(
            'livewire.pages.training-module-show',
            [
                'completedLessonIds' => $completedLessonIds,
            ]
        );
    }
}
