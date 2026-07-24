<?php

namespace App\Filament\Pages;

use App\Models\Department;
use App\Models\FunTestQuestion;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class FunTestBuilder extends Page
{
    /*
    |--------------------------------------------------------------------------
    | FILAMENT PAGE
    |--------------------------------------------------------------------------
    */

    protected string $view =
        'filament.pages.fun-test-builder';

    protected static ?string $navigationLabel =
        'Fun Test Builder';

    protected static ?string $title =
        'Fun Test Builder';

    protected static ?int $navigationSort =
        40;

    /*
    |--------------------------------------------------------------------------
    | GENERAL STATE
    |--------------------------------------------------------------------------
    */

    public ?int $editingId = null;

    public ?int $departmentId = null;

    public string $mode =
        FunTestQuestion::MODE_MATCH_IT;

    /*
     * Jangan gunakan $title karena Filament Page
     * sudah memiliki static property $title.
     */
    public string $funTestTitle = '';

    public string $prompt = '';

    public string $explanation = '';

    public bool $isActive = true;

    /*
    |--------------------------------------------------------------------------
    | DRAG & DROP / MATCH IT
    |--------------------------------------------------------------------------
    */

    public array $items = [];

    /*
    |--------------------------------------------------------------------------
    | WORD SCRAMBLE
    |--------------------------------------------------------------------------
    */

    public string $word = '';

    public string $scrambled = '';

    public string $hint = '';

    /*
    |--------------------------------------------------------------------------
    | SPEED QUIZ
    |--------------------------------------------------------------------------
    */

    public string $optionA = '';

    public string $optionB = '';

    public string $optionC = '';

    public string $optionD = '';

    public string $correctAnswer = 'A';

    public int $timeLimitSeconds = 5;

    /*
    |--------------------------------------------------------------------------
    | TRUE / FALSE
    |--------------------------------------------------------------------------
    */

    public string $booleanAnswer = 'true';

    /*
    |--------------------------------------------------------------------------
    | FILL BLANK
    |--------------------------------------------------------------------------
    */

    public string $textAnswer = '';

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
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
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $user = auth()->user();

        abort_unless(
            $user
            && $user->canManageLearningContent(),
            403
        );

        /*
         * Contributor otomatis memakai department sendiri.
         * Super Admin dapat memilih department.
         */
        if (! $user->isSuperAdmin()) {
            $this->departmentId =
                $user->department_id;
        }

        $this->initializeItems();
    }

    /*
    |--------------------------------------------------------------------------
    | MODE CHANGED
    |--------------------------------------------------------------------------
    */

    public function updatedMode(): void
    {
        $this->initializeItems();

        $this->word = '';

        $this->scrambled = '';

        $this->hint = '';

        $this->optionA = '';

        $this->optionB = '';

        $this->optionC = '';

        $this->optionD = '';

        $this->correctAnswer = 'A';

        $this->timeLimitSeconds = 5;

        $this->booleanAnswer = 'true';

        $this->textAnswer = '';
    }

    /*
    |--------------------------------------------------------------------------
    | ITEMS
    |--------------------------------------------------------------------------
    */

    public function addItem(): void
    {
        $this->items[] =
            $this->blankItem();
    }

    public function removeItem(
        int $index
    ): void {
        if (
            ! array_key_exists(
                $index,
                $this->items
            )
        ) {
            return;
        }

        unset(
            $this->items[$index]
        );

        $this->items =
            array_values(
                $this->items
            );

        /*
         * Minimal selalu ada satu item input.
         */
        if ($this->items === []) {
            $this->initializeItems();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

    public function saveFunTest(): void
    {
        $user = auth()->user();

        abort_unless(
            $user
            && $user->canManageLearningContent(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */

        $departmentId =
            $user->isSuperAdmin()
                ? $this->departmentId
                : $user->department_id;

        /*
        |--------------------------------------------------------------------------
        | BASE VALIDATION
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'departmentId' => [
                'required',
                'integer',
                'exists:departments,id',
            ],

            'mode' => [
                'required',
                'string',
                'in:'
                . implode(
                    ',',
                    [
                        FunTestQuestion::MODE_DRAG_DROP,
                        FunTestQuestion::MODE_MATCH_IT,
                        FunTestQuestion::MODE_WORD_SCRAMBLE,
                        FunTestQuestion::MODE_SPEED_QUIZ,
                        FunTestQuestion::MODE_TRUE_FALSE,
                        FunTestQuestion::MODE_FILL_BLANK,
                    ]
                ),
            ],

            'funTestTitle' => [
                'required',
                'string',
                'max:255',
            ],

            'prompt' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MODE VALIDATION
        |--------------------------------------------------------------------------
        */

        $this->validateModeSpecificFields();

        /*
        |--------------------------------------------------------------------------
        | SECURITY: CONTRIBUTOR DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if (
            ! $user->isSuperAdmin()
            && (int) $departmentId
                !== (int) $user->department_id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | BUILD JSON
        |--------------------------------------------------------------------------
        */

        [
            $payload,
            $answer,
        ] = $this->buildPayloadAndAnswer();

        /*
        |--------------------------------------------------------------------------
        | CREATE / UPDATE
        |--------------------------------------------------------------------------
        */

        if ($this->editingId) {
            $record =
                FunTestQuestion::query()
                    ->manageableBy($user)
                    ->findOrFail(
                        $this->editingId
                    );
        } else {
            $record =
                new FunTestQuestion();
        }

        $record->fill([
            'department_id' =>
                $departmentId,

            'created_by' =>
                $record->exists
                    ? $record->created_by
                    : $user->id,

            'mode' =>
                $this->mode,

            'title' =>
                trim(
                    $this->funTestTitle
                ),

            'prompt' =>
                trim(
                    $this->prompt
                ),

            'payload' =>
                $payload,

            'answer' =>
                $answer,

            'explanation' =>
                filled(
                    $this->explanation
                )
                    ? trim(
                        $this->explanation
                    )
                    : null,

            'is_active' =>
                $this->isActive,
        ]);

        $record->save();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        Notification::make()
            ->success()
            ->title(
                $this->editingId
                    ? 'Fun Test berhasil diperbarui'
                    : 'Fun Test berhasil dibuat'
            )
            ->body(
                'Data Fun Test berhasil disimpan.'
            )
            ->send();

        $this->resetBuilder();
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        int $id
    ): void {
        $record =
            FunTestQuestion::query()
                ->manageableBy(
                    auth()->user()
                )
                ->findOrFail($id);

        $this->editingId =
            $record->id;

        $this->departmentId =
            $record->department_id;

        $this->mode =
            $record->mode;

        $this->funTestTitle =
            $record->title
            ?? '';

        $this->prompt =
            $record->prompt
            ?? '';

        $this->explanation =
            $record->explanation
            ?? '';

        $this->isActive =
            (bool) $record->is_active;

        /*
        |--------------------------------------------------------------------------
        | JSON
        |--------------------------------------------------------------------------
        */

        $payload =
            $record->payload
            ?? [];

        $answer =
            $record->answer
            ?? [];

        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        $this->items =
            $payload['items']
            ?? [
                $this->blankItem(),
            ];

        /*
        |--------------------------------------------------------------------------
        | WORD SCRAMBLE
        |--------------------------------------------------------------------------
        */

        $this->word =
            $payload['word']
            ?? '';

        $this->scrambled =
            $payload['scrambled']
            ?? '';

        $this->hint =
            $payload['hint']
            ?? '';

        /*
        |--------------------------------------------------------------------------
        | SPEED QUIZ
        |--------------------------------------------------------------------------
        */

        $options =
            $payload['options']
            ?? [];

        $this->optionA =
            $options['A']
            ?? '';

        $this->optionB =
            $options['B']
            ?? '';

        $this->optionC =
            $options['C']
            ?? '';

        $this->optionD =
            $options['D']
            ?? '';

        $this->timeLimitSeconds =
            (int) (
                $payload[
                    'time_limit_seconds'
                ]
                ?? 5
            );

        /*
        |--------------------------------------------------------------------------
        | ANSWER
        |--------------------------------------------------------------------------
        */

        if (
            $record->mode
            ===
            FunTestQuestion::MODE_SPEED_QUIZ
        ) {
            $this->correctAnswer =
                (string) (
                    $answer['value']
                    ?? 'A'
                );
        }

        if (
            $record->mode
            ===
            FunTestQuestion::MODE_TRUE_FALSE
        ) {
            $this->booleanAnswer =
                (string) (
                    $answer['value']
                    ?? 'true'
                );
        }

        if (
            $record->mode
            ===
            FunTestQuestion::MODE_FILL_BLANK
        ) {
            $this->textAnswer =
                (string) (
                    $answer['value']
                    ?? ''
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): void {
        $record =
            FunTestQuestion::query()
                ->manageableBy(
                    auth()->user()
                )
                ->findOrFail($id);

        $record->delete();

        if (
            $this->editingId
            === $id
        ) {
            $this->resetBuilder();
        }

        Notification::make()
            ->success()
            ->title(
                'Fun Test berhasil dihapus'
            )
            ->send();
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancelEdit(): void
    {
        $this->resetBuilder();
    }

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    public function getDepartments():
        array
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            return Department::query()
                ->whereKey(
                    $user->department_id
                )
                ->pluck(
                    'name',
                    'id'
                )
                ->all();
        }

        return Department::query()
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->pluck(
                'name',
                'id'
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | RECORDS
    |--------------------------------------------------------------------------
    */

    public function getRecords()
    {
        return FunTestQuestion::query()
            ->manageableBy(
                auth()->user()
            )
            ->with([
                'department',
                'creator',
            ])
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | MODE VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateModeSpecificFields():
        void
    {
        /*
        |--------------------------------------------------------------------------
        | DRAG DROP
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_DRAG_DROP
        ) {
            $this->validate([
                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.category' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'items.*.term' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MATCH IT
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_MATCH_IT
        ) {
            $this->validate([
                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.left' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'items.*.right' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | WORD SCRAMBLE
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_WORD_SCRAMBLE
        ) {
            $this->validate([
                'word' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'scrambled' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'hint' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SPEED QUIZ
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_SPEED_QUIZ
        ) {
            $this->validate([
                'optionA' => [
                    'required',
                    'string',
                ],

                'optionB' => [
                    'required',
                    'string',
                ],

                'optionC' => [
                    'required',
                    'string',
                ],

                'optionD' => [
                    'required',
                    'string',
                ],

                'correctAnswer' => [
                    'required',
                    'in:A,B,C,D',
                ],

                'timeLimitSeconds' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:300',
                ],
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | TRUE FALSE
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_TRUE_FALSE
        ) {
            $this->validate([
                'booleanAnswer' => [
                    'required',
                    'in:true,false',
                ],
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FILL BLANK
        |--------------------------------------------------------------------------
        */

        if (
            $this->mode
            ===
            FunTestQuestion::MODE_FILL_BLANK
        ) {
            $this->validate([
                'textAnswer' => [
                    'required',
                    'string',
                    'max:1000',
                ],
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD PAYLOAD
    |--------------------------------------------------------------------------
    */

    private function buildPayloadAndAnswer():
        array
    {
        return match (
            $this->mode
        ) {
            /*
            |--------------------------------------------------------------------------
            | DRAG DROP
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_DRAG_DROP =>
                [
                    [
                        'items' =>
                            array_map(
                                fn (
                                    array $item
                                ): array => [
                                    'category' =>
                                        trim(
                                            $item[
                                                'category'
                                            ]
                                            ?? ''
                                        ),

                                    'term' =>
                                        trim(
                                            $item[
                                                'term'
                                            ]
                                            ?? ''
                                        ),
                                ],
                                $this->items
                            ),
                    ],

                    [],
                ],

            /*
            |--------------------------------------------------------------------------
            | MATCH IT
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_MATCH_IT =>
                [
                    [
                        'items' =>
                            array_map(
                                fn (
                                    array $item
                                ): array => [
                                    'left' =>
                                        trim(
                                            $item[
                                                'left'
                                            ]
                                            ?? ''
                                        ),

                                    'right' =>
                                        trim(
                                            $item[
                                                'right'
                                            ]
                                            ?? ''
                                        ),
                                ],
                                $this->items
                            ),
                    ],

                    [],
                ],

            /*
            |--------------------------------------------------------------------------
            | WORD SCRAMBLE
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_WORD_SCRAMBLE =>
                [
                    [
                        'word' =>
                            trim(
                                $this->word
                            ),

                        'scrambled' =>
                            trim(
                                $this->scrambled
                            ),

                        'hint' =>
                            trim(
                                $this->hint
                            ),
                    ],

                    [
                        'value' =>
                            trim(
                                $this->word
                            ),
                    ],
                ],

            /*
            |--------------------------------------------------------------------------
            | SPEED QUIZ
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_SPEED_QUIZ =>
                [
                    [
                        'options' => [
                            'A' =>
                                trim(
                                    $this->optionA
                                ),

                            'B' =>
                                trim(
                                    $this->optionB
                                ),

                            'C' =>
                                trim(
                                    $this->optionC
                                ),

                            'D' =>
                                trim(
                                    $this->optionD
                                ),
                        ],

                        'time_limit_seconds' =>
                            $this
                                ->timeLimitSeconds,
                    ],

                    [
                        'value' =>
                            $this
                                ->correctAnswer,
                    ],
                ],

            /*
            |--------------------------------------------------------------------------
            | TRUE FALSE
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_TRUE_FALSE =>
                [
                    [],

                    [
                        'value' =>
                            $this
                                ->booleanAnswer,
                    ],
                ],

            /*
            |--------------------------------------------------------------------------
            | FILL BLANK
            |--------------------------------------------------------------------------
            */

            FunTestQuestion::MODE_FILL_BLANK =>
                [
                    [],

                    [
                        'value' =>
                            trim(
                                $this->textAnswer
                            ),
                    ],
                ],

            default =>
                [
                    [],
                    [],
                ],
        };
    }

    /*
    |--------------------------------------------------------------------------
    | RESET BUILDER
    |--------------------------------------------------------------------------
    */

    private function resetBuilder(): void
    {
        $user = auth()->user();

        $this->editingId =
            null;

        $this->departmentId =
            $user->isSuperAdmin()
                ? null
                : $user->department_id;

        $this->mode =
            FunTestQuestion::MODE_MATCH_IT;

        $this->funTestTitle = '';

        $this->prompt = '';

        $this->explanation = '';

        $this->isActive = true;

        $this->word = '';

        $this->scrambled = '';

        $this->hint = '';

        $this->optionA = '';

        $this->optionB = '';

        $this->optionC = '';

        $this->optionD = '';

        $this->correctAnswer = 'A';

        $this->timeLimitSeconds = 5;

        $this->booleanAnswer = 'true';

        $this->textAnswer = '';

        $this->initializeItems();
    }

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE ITEMS
    |--------------------------------------------------------------------------
    |
    | Method ini WAJIB ADA.
    |
    | Ini yang sebelumnya hilang sehingga muncul:
    |
    | Method FunTestBuilder::resetItems does not exist
    |
    */

    private function initializeItems(): void
    {
        $this->items = [
            $this->blankItem(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BLANK ITEM
    |--------------------------------------------------------------------------
    */

    private function blankItem(): array
    {
        return [
            'left' =>
                '',

            'right' =>
                '',

            'category' =>
                '',

            'term' =>
                '',
        ];
    }
}