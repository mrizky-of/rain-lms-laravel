<?php

namespace App\Filament\Pages;

use App\Models\Question;
use App\Models\Quiz;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class QuestionBuilder extends Page
{
    /*
    |--------------------------------------------------------------------------
    | Filament Page Configuration
    |--------------------------------------------------------------------------
    */

    protected string $view =
        'filament.pages.question-builder';

    protected static ?string $navigationLabel =
        'Question Builder';

    protected static ?string $title =
        'Question Builder';

    protected static ?int $navigationSort =
        30;

    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    public ?int $quizId = null;

    public array $questions = [];

    public bool $locked = false;

    /*
    |--------------------------------------------------------------------------
    | Save Feedback
    |--------------------------------------------------------------------------
    */

    public ?string $saveMessage = null;

    public ?string $saveError = null;

    /*
    |--------------------------------------------------------------------------
    | Debug Connection
    |--------------------------------------------------------------------------
    |
    | Sementara dipakai untuk memastikan action Livewire bekerja.
    |
    */

    public int $connectionTestCount = 0;

    /*
    |--------------------------------------------------------------------------
    | Access
    |--------------------------------------------------------------------------
    */

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user
            && $user->canManageLearningContent();
    }

    /*
    |--------------------------------------------------------------------------
    | Test Livewire Connection
    |--------------------------------------------------------------------------
    */

    public function testConnection(): void
    {
        $this->connectionTestCount++;

        $this->saveMessage =
            'Livewire aktif. Action berhasil dipanggil.';

        $this->saveError = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Quiz Options
    |--------------------------------------------------------------------------
    */

    public function getQuizOptions(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        return Quiz::query()
            ->manageableBy($user)
            ->with('trainingModule')
            ->orderBy('title')
            ->get()
            ->mapWithKeys(
                function (Quiz $quiz): array {
                    $moduleTitle =
                        $quiz
                            ->trainingModule
                            ?->title
                        ?? '-';

                    return [
                        $quiz->id =>
                            $quiz->title
                            . ' — '
                            . $moduleTitle
                            . ' ('
                            . $quiz->question_count
                            . ' soal)',
                    ];
                }
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Quiz Selected
    |--------------------------------------------------------------------------
    */

    public function updatedQuizId(
        mixed $value
    ): void {
        $this->saveMessage = null;

        $this->saveError = null;

        /*
        |--------------------------------------------------------------------------
        | Empty Selection
        |--------------------------------------------------------------------------
        */

        if (
            blank($value)
            || (int) $value <= 0
        ) {
            $this->quizId = null;

            $this->questions = [];

            $this->locked = false;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize ID
        |--------------------------------------------------------------------------
        */

        $this->quizId =
            (int) $value;

        /*
        |--------------------------------------------------------------------------
        | Resolve Quiz
        |--------------------------------------------------------------------------
        */

        $quiz =
            $this->resolveQuiz(
                $this->quizId
            );

        /*
        |--------------------------------------------------------------------------
        | Lock When Quiz Already Has Attempts
        |--------------------------------------------------------------------------
        */

        $this->locked =
            $quiz
                ->attempts()
                ->exists();

        /*
        |--------------------------------------------------------------------------
        | Existing Questions
        |--------------------------------------------------------------------------
        */

        $existingQuestions =
            $quiz
                ->questions()
                ->orderBy('sort_order')
                ->get()
                ->keyBy('sort_order');

        /*
        |--------------------------------------------------------------------------
        | Generate Templates
        |--------------------------------------------------------------------------
        */

        $templates = [];

        for (
            $number = 1;
            $number <=
                (int) $quiz->question_count;
            $number++
        ) {
            $existing =
                $existingQuestions->get(
                    $number
                );

            $options =
                is_array(
                    $existing?->options
                )
                    ? $existing->options
                    : [];

            $templates[] = [
                'id' =>
                    $existing?->id,

                'type' =>
                    $existing?->type
                    ?? Question::TYPE_MULTIPLE_CHOICE,

                'question_text' =>
                    $existing?->question_text
                    ?? '',

                'option_a' =>
                    $options['A']
                    ?? '',

                'option_b' =>
                    $options['B']
                    ?? '',

                'option_c' =>
                    $options['C']
                    ?? '',

                'option_d' =>
                    $options['D']
                    ?? '',

                'correct_answer' =>
                    $existing?->correct_answer
                    ?? 'A',

                'essay_answer_key' =>
                    $existing?->essay_answer_key
                    ?? '',

                'explanation' =>
                    $existing?->explanation
                    ?? '',

                'points' =>
                    (float) (
                        $existing?->points
                        ?? 1
                    ),
            ];
        }

        $this->questions =
            $templates;
    }

    /*
    |--------------------------------------------------------------------------
    | Save Questions
    |--------------------------------------------------------------------------
    */

    public function saveQuestions(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Reset Feedback
        |--------------------------------------------------------------------------
        */

        $this->saveMessage = null;

        $this->saveError = null;

        /*
        |--------------------------------------------------------------------------
        | Debug Log
        |--------------------------------------------------------------------------
        |
        | Kalau tombol benar-benar masuk ke PHP,
        | pesan ini akan muncul di laravel.log.
        |
        */

        Log::info(
            'QuestionBuilder::saveQuestions called',
            [
                'user_id' =>
                    auth()->id(),

                'quiz_id' =>
                    $this->quizId,

                'question_templates' =>
                    count(
                        $this->questions
                    ),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Quiz Required
        |--------------------------------------------------------------------------
        */

        if (! $this->quizId) {
            $this->saveError =
                'Silakan pilih Quiz terlebih dahulu.';

            Notification::make()
                ->danger()
                ->title(
                    'Quiz belum dipilih'
                )
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Quiz
        |--------------------------------------------------------------------------
        */

        try {
            $quiz =
                $this->resolveQuiz(
                    (int) $this->quizId
                );
        } catch (Throwable $exception) {
            report($exception);

            $this->saveError =
                'Quiz tidak ditemukan atau Anda tidak memiliki akses ke Quiz tersebut.';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Lock Check
        |--------------------------------------------------------------------------
        */

        if (
            $quiz
                ->attempts()
                ->exists()
        ) {
            $this->locked = true;

            $this->saveError =
                'Quiz sudah pernah dikerjakan. Soal dikunci agar data hasil test lama tidak berubah.';

            Notification::make()
                ->danger()
                ->title(
                    'Question Builder terkunci'
                )
                ->body(
                    $this->saveError
                )
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Count Check
        |--------------------------------------------------------------------------
        */

        if (
            count(
                $this->questions
            )
            !==
            (int)
            $quiz->question_count
        ) {
            $this->saveError =
                'Jumlah template soal tidak sesuai dengan question_count Quiz.';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Base Validation
        |--------------------------------------------------------------------------
        */

        try {
            $this->validate([
                'quizId' => [
                    'required',
                    'integer',
                ],

                'questions' => [
                    'required',
                    'array',
                    'size:'
                    . $quiz->question_count,
                ],

                'questions.*.type' => [
                    'required',

                    Rule::in([
                        Question::TYPE_MULTIPLE_CHOICE,
                        Question::TYPE_ESSAY,
                    ]),
                ],

                'questions.*.question_text' => [
                    'required',
                    'string',
                    'max:10000',
                ],

                'questions.*.points' => [
                    'required',
                    'numeric',
                    'min:0.1',
                ],

                'questions.*.explanation' => [
                    'nullable',
                    'string',
                    'max:10000',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Per-Question Validation
            |--------------------------------------------------------------------------
            */

            foreach (
                $this->questions
                as $index => $question
            ) {
                $number =
                    $index + 1;

                /*
                |--------------------------------------------------------------------------
                | Multiple Choice
                |--------------------------------------------------------------------------
                */

                if (
                    (
                        $question['type']
                        ?? null
                    )
                    ===
                    Question::TYPE_MULTIPLE_CHOICE
                ) {
                    $requiredOptions = [
                        'option_a' =>
                            'Pilihan A',

                        'option_b' =>
                            'Pilihan B',

                        'option_c' =>
                            'Pilihan C',

                        'option_d' =>
                            'Pilihan D',
                    ];

                    foreach (
                        $requiredOptions
                        as $field => $label
                    ) {
                        if (
                            blank(
                                $question[
                                    $field
                                ]
                                ?? null
                            )
                        ) {
                            throw ValidationException::withMessages([
                                "questions.{$index}.{$field}" =>
                                    "{$label} pada soal {$number} wajib diisi.",
                            ]);
                        }
                    }

                    if (
                        ! in_array(
                            $question[
                                'correct_answer'
                            ]
                            ?? null,
                            [
                                'A',
                                'B',
                                'C',
                                'D',
                            ],
                            true
                        )
                    ) {
                        throw ValidationException::withMessages([
                            "questions.{$index}.correct_answer" =>
                                "Jawaban benar soal {$number} harus A, B, C, atau D.",
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Essay
                |--------------------------------------------------------------------------
                */

                if (
                    (
                        $question['type']
                        ?? null
                    )
                    ===
                    Question::TYPE_ESSAY
                    &&
                    blank(
                        $question[
                            'essay_answer_key'
                        ]
                        ?? null
                    )
                ) {
                    throw ValidationException::withMessages([
                        "questions.{$index}.essay_answer_key" =>
                            "Kunci / referensi jawaban essay soal {$number} wajib diisi.",
                    ]);
                }
            }
        } catch (
            ValidationException $exception
        ) {
            /*
             * Biarkan Livewire menampilkan
             * validation bag.
             */

            $this->saveError =
                'Data belum lengkap. Periksa pesan error pada setiap soal.';

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Save Database
        |--------------------------------------------------------------------------
        */

        try {
            DB::transaction(
                function () use (
                    $quiz
                ): void {
                    foreach (
                        $this->questions
                        as $index => $data
                    ) {
                        $sortOrder =
                            $index + 1;

                        $isMultipleChoice =
                            (
                                $data['type']
                                ?? null
                            )
                            ===
                            Question::TYPE_MULTIPLE_CHOICE;

                        /*
                        |--------------------------------------------------------------------------
                        | Options
                        |--------------------------------------------------------------------------
                        */

                        $options =
                            $isMultipleChoice
                                ? [
                                    'A' =>
                                        trim(
                                            (string)
                                            $data[
                                                'option_a'
                                            ]
                                        ),

                                    'B' =>
                                        trim(
                                            (string)
                                            $data[
                                                'option_b'
                                            ]
                                        ),

                                    'C' =>
                                        trim(
                                            (string)
                                            $data[
                                                'option_c'
                                            ]
                                        ),

                                    'D' =>
                                        trim(
                                            (string)
                                            $data[
                                                'option_d'
                                            ]
                                        ),
                                ]
                                : null;

                        /*
                        |--------------------------------------------------------------------------
                        | Data
                        |--------------------------------------------------------------------------
                        */

                        $saveData = [
                            'type' =>
                                $data['type'],

                            'question_text' =>
                                trim(
                                    (string)
                                    $data[
                                        'question_text'
                                    ]
                                ),

                            'options' =>
                                $options,

                            'correct_answer' =>
                                $isMultipleChoice
                                    ? (
                                        $data[
                                            'correct_answer'
                                        ]
                                        ?? null
                                    )
                                    : null,

                            'essay_answer_key' =>
                                ! $isMultipleChoice
                                    ? trim(
                                        (string) (
                                            $data[
                                                'essay_answer_key'
                                            ]
                                            ?? ''
                                        )
                                    )
                                    : null,

                            'explanation' =>
                                filled(
                                    $data[
                                        'explanation'
                                    ]
                                    ?? null
                                )
                                    ? trim(
                                        (string)
                                        $data[
                                            'explanation'
                                        ]
                                    )
                                    : null,

                            'points' =>
                                (float)
                                $data[
                                    'points'
                                ],
                        ];

                        /*
                        |--------------------------------------------------------------------------
                        | Update/Create
                        |--------------------------------------------------------------------------
                        */

                        Question::query()
                            ->updateOrCreate(
                                [
                                    'quiz_id' =>
                                        $quiz->id,

                                    'sort_order' =>
                                        $sortOrder,
                                ],
                                $saveData
                            );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Excess Questions
                    |--------------------------------------------------------------------------
                    */

                    $quiz
                        ->questions()
                        ->where(
                            'sort_order',
                            '>',
                            (int)
                            $quiz->question_count
                        )
                        ->delete();
                }
            );
        } catch (Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Database Error Visible
            |--------------------------------------------------------------------------
            */

            Log::error(
                'QuestionBuilder save failed',
                [
                    'quiz_id' =>
                        $quiz->id,

                    'message' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),
                ]
            );

            report($exception);

            $this->saveError =
                'Database gagal menyimpan soal: '
                . $exception->getMessage();

            Notification::make()
                ->danger()
                ->title(
                    'Gagal menyimpan Question'
                )
                ->body(
                    'Lihat pesan error merah pada halaman.'
                )
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Save
        |--------------------------------------------------------------------------
        */

        $actualCount =
            Question::query()
                ->where(
                    'quiz_id',
                    $quiz->id
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Reload Templates
        |--------------------------------------------------------------------------
        */

        $this->updatedQuizId(
            $quiz->id
        );

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        $this->saveMessage =
            "{$actualCount} soal berhasil disimpan untuk quiz \"{$quiz->title}\".";

        $this->saveError =
            null;

        Notification::make()
            ->success()
            ->title(
                'Question berhasil disimpan'
            )
            ->body(
                $this->saveMessage
            )
            ->send();

        Log::info(
            'QuestionBuilder save successful',
            [
                'quiz_id' =>
                    $quiz->id,

                'saved_questions' =>
                    $actualCount,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Quiz
    |--------------------------------------------------------------------------
    */

    private function resolveQuiz(
        int $quizId
    ): Quiz {
        $user = auth()->user();

        abort_unless(
            $user
            && $user
                ->canManageLearningContent(),
            403
        );

        return Quiz::query()
            ->manageableBy($user)
            ->with([
                'trainingModule',
            ])
            ->findOrFail(
                $quizId
            );
    }
}