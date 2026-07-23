<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\KpiLedger;
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
            ) {
                $quiz->load([
                    'trainingModule',
                    'questions.options',
                ]);

                $attemptCount = QuizAttempt::query()
                    ->where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->count();

                if (
                    $attemptCount
                    >= $quiz->max_attempts
                ) {
                    throw ValidationException::withMessages([
                        'quiz' => 'Batas percobaan test sudah habis.',
                    ]);
                }

                $attempt = QuizAttempt::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'attempt_number' => $attemptCount + 1,
                    'started_at' => now(),
                ]);

                $totalPoints = 0;
                $earnedPoints = 0;

                foreach (
                    $quiz->questions as $question
                ) {
                    $totalPoints += $question->points;

                    $selectedOptionId =
                        $answers[$question->id]
                        ?? null;

                    $selectedOption =
                        $question
                            ->options
                            ->firstWhere(
                                'id',
                                $selectedOptionId
                            );

                    $isCorrect =
                        (bool) (
                            $selectedOption
                                ?->is_correct
                        );

                    $pointsEarned =
                        $isCorrect
                            ? $question->points
                            : 0;

                    $earnedPoints +=
                        $pointsEarned;

                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,

                        'question_id' => $question->id,

                        'question_option_id' => $selectedOption?->id,

                        'is_correct' => $isCorrect,

                        'points_earned' => $pointsEarned,
                    ]);
                }

                $score = $totalPoints > 0
                    ? round(
                        (
                            $earnedPoints
                            / $totalPoints
                        ) * 100,
                        2
                    )
                    : 0;

                $passed =
                    $score
                    >=
                    $quiz
                        ->trainingModule
                        ->passing_score;

                $attempt->update([
                    'score' => $score,
                    'passed' => $passed,
                    'submitted_at' => now(),
                ]);

                $enrollment =
                    Enrollment::firstOrCreate(
                        [
                            'user_id' => $user->id,

                            'training_module_id' => $quiz->training_module_id,
                        ],
                        [
                            'source' => 'self',
                            'status' => 'in_progress',
                            'started_at' => now(),
                        ]
                    );

                if ($passed) {
                    $enrollment->update([
                        'status' => 'completed',
                        'progress_percentage' => 100,
                        'completed_at' => now(),
                    ]);

                    KpiLedger::firstOrCreate(
                        [
                            'user_id' => $user->id,

                            'training_module_id' => $quiz->training_module_id,

                            'source' => 'module_completion',
                        ],
                        [
                            'points' => $quiz
                                ->trainingModule
                                ->kpi_points,

                            'description' => 'Poin kelulusan modul '
                                .$quiz
                                    ->trainingModule
                                    ->title,

                            'earned_at' => now(),
                        ]
                    );

                    Certificate::firstOrCreate(
                        [
                            'user_id' => $user->id,

                            'training_module_id' => $quiz->training_module_id,
                        ],
                        [
                            'certificate_number' => 'RAIN-'
                                .now()->format('Ymd')
                                .'-'
                                .strtoupper(
                                    Str::random(8)
                                ),

                            'issued_at' => now(),
                        ]
                    );
                }

                return $attempt->fresh();
            }
        );
    }
}
