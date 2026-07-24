<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | QUIZ ANSWERS
        |--------------------------------------------------------------------------
        |
        | Struktur lama:
        |
        | question_options
        |       ↓
        | selected_option_id
        |
        | Struktur baru:
        |
        | Multiple Choice:
        | selected_answer = A/B/C/D
        |
        | Essay:
        | answer_text = jawaban user
        |
        | Penilaian:
        | is_correct
        | points_awarded
        | manual_score
        |
        */

        if (! Schema::hasTable('quiz_answers')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SELECTED ANSWER
        |--------------------------------------------------------------------------
        |
        | Multiple choice menyimpan:
        |
        | A
        | B
        | C
        | D
        |
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'selected_answer'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->string(
                        'selected_answer',
                        10
                    )->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ESSAY ANSWER
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'answer_text'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->longText(
                        'answer_text'
                    )->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | IS CORRECT
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'is_correct'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->boolean(
                        'is_correct'
                    )
                        ->default(false);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | POINTS AWARDED
        |--------------------------------------------------------------------------
        |
        | INI KOLOM YANG MENYEBABKAN ERROR ANDA SEKARANG.
        |
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'points_awarded'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->decimal(
                        'points_awarded',
                        8,
                        2
                    )
                        ->default(0);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MANUAL SCORE
        |--------------------------------------------------------------------------
        |
        | Dipakai nanti untuk penilaian essay.
        |
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'manual_score'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->decimal(
                        'manual_score',
                        8,
                        2
                    )->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REVIEWED BY
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'reviewed_by'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->foreignId(
                        'reviewed_by'
                    )
                        ->nullable()
                        ->constrained('users')
                        ->nullOnDelete();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REVIEWED AT
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'quiz_answers',
                'reviewed_at'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table->timestamp(
                        'reviewed_at'
                    )->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LEGACY SELECTED OPTION
        |--------------------------------------------------------------------------
        |
        | selected_option_id berasal dari sistem lama:
        |
        | questions
        |     ↓
        | question_options
        |     ↓
        | selected_option_id
        |
        | Sistem baru TIDAK lagi memakai QuestionOption.
        |
        | Kita TIDAK drop kolom lama sekarang karena mungkin ada historical
        | data. Kita hanya memastikan nullable agar insert baru tidak wajib
        | mengisinya.
        |
        */

        if (
            Schema::hasColumn(
                'quiz_answers',
                'selected_option_id'
            )
        ) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    $table
                        ->unsignedBigInteger(
                            'selected_option_id'
                        )
                        ->nullable()
                        ->change();
                }
            );
        }
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CONSERVATIVE ROLLBACK
        |--------------------------------------------------------------------------
        |
        | Sengaja tidak menghapus kolom baru secara otomatis karena kolom
        | tersebut dapat sudah berisi hasil test user.
        |
        | Kita tidak ingin rollback tidak sengaja menghapus historical data.
        |
        */
    }
};