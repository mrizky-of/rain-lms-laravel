<?php

namespace App\Livewire\Pages;

use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\TrainingModule;
use App\Services\QuizService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Test Interaktif - RAIN LMS')]
class QuizTake extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Quiz
    |--------------------------------------------------------------------------
    */

    public Quiz $quiz;

    /*
    |--------------------------------------------------------------------------
    | Answers
    |--------------------------------------------------------------------------
    |
    | Format:
    |
    | [
    |     question_id => answer,
    | ]
    |
    | Contoh Multiple Choice:
    |
    | [
    |     2 => 'A',
    |     3 => 'B',
    | ]
    |
    | Contoh Essay:
    |
    | [
    |     4 => 'Jawaban essay user...',
    | ]
    |
    */

    public array $answers = [];

    /*
    |--------------------------------------------------------------------------
    | Result
    |--------------------------------------------------------------------------
    */

    public ?float $resultScore = null;

    public ?bool $passed = null;

    public bool $pendingReview = false;

    /*
    |--------------------------------------------------------------------------
    | Submit State
    |--------------------------------------------------------------------------
    */

    public bool $submitted = false;

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(
        Quiz $quiz
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Quiz Active
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $quiz->is_active,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Check Module Visibility
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Check Enrollment
        |--------------------------------------------------------------------------
        */

        $enrollment =
            Enrollment::query()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->where(
                    'training_module_id',
                    $quiz->training_module_id
                )
                ->first();

        /*
         * Quiz hanya boleh dikerjakan setelah
         * material selesai.
         */

        abort_unless(
            $enrollment
            &&
            (
                $enrollment->material_completed_at
                ||
                $enrollment->status
                    === 'completed'
            ),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Load Quiz
        |--------------------------------------------------------------------------
        */

        $this->quiz =
            $quiz->load([
                'trainingModule',

                'questions' =>
                    fn ($query) =>
                        $query->orderBy(
                            'sort_order'
                        ),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Check Question Completeness
        |--------------------------------------------------------------------------
        */

        abort_if(
            $this->quiz
                ->questions
                ->isEmpty(),
            404,
            'Quiz belum memiliki soal.'
        );

        /*
        |--------------------------------------------------------------------------
        | Initialize Answers
        |--------------------------------------------------------------------------
        |
        | Ini penting.
        |
        | Kita membuat key setiap question dari awal:
        |
        | answers[2] = null
        | answers[3] = null
        |
        | sehingga binding Livewire punya struktur
        | state yang konsisten.
        |
        */

        foreach (
            $this->quiz->questions
            as $question
        ) {
            $this->answers[
                $question->id
            ] = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Answer Updated
    |--------------------------------------------------------------------------
    |
    | Saat user memilih jawaban,
    | error validation untuk jawaban tersebut
    | langsung kita hapus.
    |
    */

    public function updatedAnswers(
        mixed $value,
        mixed $key
    ): void {
        $this->resetValidation(
            'answers.' . $key
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Quiz
    |--------------------------------------------------------------------------
    */

    public function submit(
        QuizService $quizService
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submit
        |--------------------------------------------------------------------------
        */

        if ($this->submitted) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Reload Quiz Questions
        |--------------------------------------------------------------------------
        */

        $this->quiz->load([
            'trainingModule',

            'questions' =>
                fn ($query) =>
                    $query->orderBy(
                        'sort_order'
                    ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Build Dynamic Validation
        |--------------------------------------------------------------------------
        */

        $rules = [];

        $messages = [];

        foreach (
            $this->quiz->questions
            as $index => $question
        ) {
            $questionNumber =
                $index + 1;

            /*
            |--------------------------------------------------------------------------
            | Essay
            |--------------------------------------------------------------------------
            */

            if (
                $question->type
                ===
                Question::TYPE_ESSAY
            ) {
                $rules[
                    "answers.{$question->id}"
                ] = [
                    'required',
                    'string',
                    'max:10000',
                ];

                $messages[
                    "answers.{$question->id}.required"
                ] =
                    "Jawaban soal {$questionNumber} wajib diisi.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Multiple Choice
            |--------------------------------------------------------------------------
            */

            $rules[
                "answers.{$question->id}"
            ] = [
                'required',
                'string',
                'in:A,B,C,D',
            ];

            $messages[
                "answers.{$question->id}.required"
            ] =
                "Jawaban soal {$questionNumber} wajib dipilih.";

            $messages[
                "answers.{$question->id}.in"
            ] =
                "Jawaban soal {$questionNumber} tidak valid.";
        }

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        try {
            $this->validate(
                $rules,
                $messages
            );
        } catch (
            ValidationException $exception
        ) {
            /*
             * Jangan tandai submitted.
             *
             * User masih dapat memperbaiki jawaban.
             */

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize Answers
        |--------------------------------------------------------------------------
        */

        $normalizedAnswers = [];

        foreach (
            $this->quiz->questions
            as $question
        ) {
            $answer =
                $this->answers[
                    $question->id
                ] ?? null;

            if (
                $question->type
                ===
                Question::TYPE_MULTIPLE_CHOICE
            ) {
                $normalizedAnswers[
                    $question->id
                ] =
                    strtoupper(
                        trim(
                            (string) $answer
                        )
                    );

                continue;
            }

            $normalizedAnswers[
                $question->id
            ] =
                trim(
                    (string) $answer
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Submit Through Service
        |--------------------------------------------------------------------------
        */

        $attempt =
            $quizService->submit(
                $this->quiz,
                auth()->user(),
                $normalizedAnswers
            );

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        $this->resultScore =
            (float)
            $attempt->score;

        $this->passed =
            (bool)
            $attempt->passed;

        $this->pendingReview =
            $attempt->status
            === 'pending_review';

        $this->submitted =
            true;

        /*
        |--------------------------------------------------------------------------
        | Clear Old Validation
        |--------------------------------------------------------------------------
        */

        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.pages.quiz-take'
        );
    }
}