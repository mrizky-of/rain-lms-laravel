<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\KpiLedger;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class QuizService
{
    public function submit(
        Quiz $quiz,
        User $user,
        array $answers
    ): QuizAttempt {
        return DB::transaction(
            function () use (
                $quiz,
                $user,
                $answers
            ): QuizAttempt {

                $quiz->load([
                    'trainingModule',
                    'questions',
                ]);

                $attemptCount =
                    QuizAttempt::query()
                        ->where(
                            'quiz_id',
                            $quiz->id
                        )
                        ->where(
                            'user_id',
                            $user->id
                        )
                        ->count();

                if (
                    $attemptCount
                    >= $quiz->max_attempts
                ) {
                    throw ValidationException::withMessages([
                        'quiz' => 'Batas maksimal percobaan quiz telah tercapai.',
                    ]);
                }

                $attempt =
                    QuizAttempt::create([
                        'quiz_id' => $quiz->id,

                        'user_id' => $user->id,

                        'attempt_number' => $attemptCount + 1,

                        'started_at' => now(),

                        'submitted_at' => now(),

                        'score' => 0,

                        'passed' => false,

                        'status' => 'submitted',
                    ]);

                $earnedPoints =
                    0.0;

                $totalPoints =
                    0.0;

                $hasEssay =
                    false;

                foreach (
                    $quiz->questions as $question
                ) {
                    $points =
                        (float)
                        $question->points;

                    $totalPoints +=
                        $points;

                    $answer =
                        $answers[
                            $question->id
                        ] ?? null;

                    if (
                        $question->type
                        ===
                        Question::TYPE_ESSAY
                    ) {
                        $hasEssay =
                            true;

                        QuizAnswer::create([
                            'quiz_attempt_id' => $attempt->id,

                            'question_id' => $question->id,

                            'answer_text' => trim(
                                (string)
                                $answer
                            ),

                            'is_correct' => false,

                            'points_awarded' => 0,
                        ]);

                        continue;
                    }

                    $isCorrect =
                        strtoupper(
                            (string)
                            $answer
                        )
                        ===
                        strtoupper(
                            (string)
                            $question
                                ->correct_answer
                        );

                    $awarded =
                        $isCorrect
                            ? $points
                            : 0;

                    $earnedPoints +=
                        $awarded;

                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,

                        'question_id' => $question->id,

                        'selected_answer' => strtoupper(
                            (string)
                            $answer
                        ),

                        'is_correct' => $isCorrect,

                        'points_awarded' => $awarded,
                    ]);
                }

                $score =
                    $totalPoints > 0
                    ? round(
                        (
                            $earnedPoints
                            / $totalPoints
                        )
                        * 100,
                        2
                    )
                    : 0;

                if ($hasEssay) {
                    $attempt->update([
                        'score' => $score,

                        'passed' => false,

                        'status' => 'pending_review',
                    ]);

                    return $attempt
                        ->fresh();
                }

                $passingScore =
                    (float)
                    $quiz
                        ->trainingModule
                        ->passing_score;

                $passed =
                    $score
                    >= $passingScore;

                $attempt->update([
                    'score' => $score,

                    'passed' => $passed,

                    'status' => $passed
                        ? 'passed'
                        : 'failed',
                ]);

                if (! $passed) {
                    return $attempt
                        ->fresh();
                }

                $enrollment =
                    Enrollment::firstOrCreate(
                        [
                            'user_id' => $user->id,

                            'training_module_id' => $quiz
                                ->training_module_id,
                        ]
                    );

                $enrollment->update([
                    'status' => 'completed',

                    'progress_percentage' => 100,

                    'completed_at' => now(),
                ]);

                KpiLedger::firstOrCreate(
                    [
                        /*
                        |--------------------------------------------------------------------------
                        | Unique Identity
                        |--------------------------------------------------------------------------
                        |
                        | Satu user hanya mendapatkan satu reward KPI
                        | untuk satu module completion.
                        |
                        */

                        'user_id' =>
                            $user->id,

                        'training_module_id' =>
                            $quiz->training_module_id,

                        'source' =>
                            'module_completion',
                    ],
                    [
                        /*
                        |--------------------------------------------------------------------------
                        | KPI Points
                        |--------------------------------------------------------------------------
                        */

                        'points' =>
                            (int) (
                                $quiz
                                    ->trainingModule
                                    ?->kpi_points
                                ?? 0
                            ),

                        /*
                        |--------------------------------------------------------------------------
                        | Description
                        |--------------------------------------------------------------------------
                        */

                        'description' =>
                            'Training module completion',

                        /*
                        |--------------------------------------------------------------------------
                        | Earned Date
                        |--------------------------------------------------------------------------
                        |
                        | INI YANG SEBELUMNYA HILANG.
                        |
                        */

                        'earned_at' =>
                            now(),
                    ]
                );

                Certificate::firstOrCreate(
                    [
                        'user_id' => $user->id,

                        'training_module_id' => $quiz
                            ->training_module_id,
                    ],
                    [
                        'certificate_number' => 'RAIN-'
                            .now()
                                ->format(
                                    'Ymd'
                                )
                            .'-'
                            .Str::upper(
                                Str::random(8)
                            ),

                        'issued_at' => now(),
                    ]
                );

                return $attempt->fresh();
            }
        );
    }
}
