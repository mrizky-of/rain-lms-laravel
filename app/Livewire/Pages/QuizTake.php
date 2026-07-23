<?php

namespace App\Livewire\Pages;

use App\Models\Quiz;
use App\Models\TrainingModule;
use App\Services\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Test Interaktif - RAIN LMS')]
class QuizTake extends Component
{
    public Quiz $quiz;

    public array $answers = [];

    public ?float $resultScore = null;

    public ?bool $passed = null;

    public function mount(
        Quiz $quiz
    ): void {
        abort_unless(
            $quiz->is_active,
            404
        );

        $allowed =
            TrainingModule::query()
                ->visibleFor(
                    auth()->user()
                )
                ->whereKey(
                    $quiz->training_module_id
                )
                ->exists();

        abort_unless(
            $allowed,
            403
        );

        $this->quiz =
            $quiz->load([
                'trainingModule',
                'questions.options',
            ]);
    }

    public function submit(
        QuizService $quizService
    ): void {
        $rules = [];

        foreach (
            $this->quiz->questions as $question
        ) {
            $rules[
                "answers.{$question->id}"
            ] = [
                'required',
                'integer',
            ];
        }

        $this->validate(
            $rules,
            [],
            [
                'answers.*' => 'jawaban',
            ]
        );

        $attempt =
            $quizService->submit(
                $this->quiz,
                auth()->user(),
                $this->answers
            );

        $this->resultScore =
            (float) $attempt->score;

        $this->passed =
            (bool) $attempt->passed;
    }

    public function render()
    {
        return view(
            'livewire.pages.quiz-take'
        );
    }
}
