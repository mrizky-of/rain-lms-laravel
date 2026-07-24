<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        |
        | Position tidak lagi menjadi master CRUD tersendiri.
        | Nama jabatan disimpan langsung di users.position_name.
        |
        | position_id lama belum kita hapus pada fase ini agar data lama aman.
        |
        */

        if (
            Schema::hasTable('users')
            && ! Schema::hasColumn('users', 'position_name')
        ) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('position_name')
                    ->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | COPY LEGACY POSITION NAME
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('positions')
            && Schema::hasColumn('users', 'position_id')
            && Schema::hasColumn('users', 'position_name')
        ) {
            $positions = DB::table('positions')
                ->pluck('name', 'id');

            DB::table('users')
                ->whereNotNull('position_id')
                ->orderBy('id')
                ->chunkById(
                    100,
                    function ($users) use ($positions): void {
                        foreach ($users as $user) {
                            $positionName =
                                $positions[
                                    $user->position_id
                                ] ?? null;

                            if (! $positionName) {
                                continue;
                            }

                            DB::table('users')
                                ->where('id', $user->id)
                                ->update([
                                    'position_name' => $positionName,
                                ]);
                        }
                    }
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TRAINING MODULES
        |--------------------------------------------------------------------------
        |
        | Materi file langsung disimpan pada Module.
        |
        */

        if (Schema::hasTable('training_modules')) {
            Schema::table(
                'training_modules',
                function (Blueprint $table): void {
                    if (
                        ! Schema::hasColumn(
                            'training_modules',
                            'material_files'
                        )
                    ) {
                        $table->json('material_files')
                            ->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'training_modules',
                            'material_file_names'
                        )
                    ) {
                        $table->json(
                            'material_file_names'
                        )->nullable();
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUIZZES
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('quizzes')) {
            Schema::table(
                'quizzes',
                function (Blueprint $table): void {
                    if (
                        ! Schema::hasColumn(
                            'quizzes',
                            'question_count'
                        )
                    ) {
                        $table->unsignedInteger(
                            'question_count'
                        )->default(10);
                    }

                    if (
                        ! Schema::hasColumn(
                            'quizzes',
                            'created_by'
                        )
                    ) {
                        $table->foreignId(
                            'created_by'
                        )
                            ->nullable()
                            ->constrained('users')
                            ->nullOnDelete();
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUESTIONS
        |--------------------------------------------------------------------------
        |
        | Question Options tidak lagi diperlukan untuk input baru.
        |
        | MC:
        | options = JSON
        | correct_answer = A/B/C/D
        |
        | Essay:
        | essay_answer_key = referensi jawaban
        |
        */

        if (Schema::hasTable('questions')) {
            Schema::table(
                'questions',
                function (Blueprint $table): void {
                    if (
                        ! Schema::hasColumn(
                            'questions',
                            'type'
                        )
                    ) {
                        $table->string('type')
                            ->default(
                                'multiple_choice'
                            );
                    }

                    if (
                        ! Schema::hasColumn(
                            'questions',
                            'options'
                        )
                    ) {
                        $table->json('options')
                            ->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'questions',
                            'correct_answer'
                        )
                    ) {
                        $table->string(
                            'correct_answer'
                        )->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'questions',
                            'essay_answer_key'
                        )
                    ) {
                        $table->text(
                            'essay_answer_key'
                        )->nullable();
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUIZ ANSWERS
        |--------------------------------------------------------------------------
        |
        | Kolom lama selected_option_id tetap dibiarkan sementara
        | untuk menjaga historical data.
        |
        */

        if (Schema::hasTable('quiz_answers')) {
            Schema::table(
                'quiz_answers',
                function (Blueprint $table): void {
                    if (
                        ! Schema::hasColumn(
                            'quiz_answers',
                            'selected_answer'
                        )
                    ) {
                        $table->string(
                            'selected_answer'
                        )->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'quiz_answers',
                            'answer_text'
                        )
                    ) {
                        $table->longText(
                            'answer_text'
                        )->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'quiz_answers',
                            'manual_score'
                        )
                    ) {
                        $table->decimal(
                            'manual_score',
                            8,
                            2
                        )->nullable();
                    }

                    if (
                        ! Schema::hasColumn(
                            'quiz_answers',
                            'reviewed_by'
                        )
                    ) {
                        $table->foreignId(
                            'reviewed_by'
                        )
                            ->nullable()
                            ->constrained('users')
                            ->nullOnDelete();
                    }

                    if (
                        ! Schema::hasColumn(
                            'quiz_answers',
                            'reviewed_at'
                        )
                    ) {
                        $table->timestamp(
                            'reviewed_at'
                        )->nullable();
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUIZ ATTEMPTS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('quiz_attempts')
            && ! Schema::hasColumn(
                'quiz_attempts',
                'status'
            )
        ) {
            Schema::table(
                'quiz_attempts',
                function (Blueprint $table): void {
                    $table->string('status')
                        ->default('submitted');
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ENROLLMENTS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('enrollments')
            && ! Schema::hasColumn(
                'enrollments',
                'material_completed_at'
            )
        ) {
            Schema::table(
                'enrollments',
                function (Blueprint $table): void {
                    $table->timestamp(
                        'material_completed_at'
                    )->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTOR PROFILES
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable(
                'contributor_profiles'
            )
            && ! Schema::hasColumn(
                'contributor_profiles',
                'is_active'
            )
        ) {
            Schema::table(
                'contributor_profiles',
                function (Blueprint $table): void {
                    $table->boolean(
                        'is_active'
                    )->default(true);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FUN TEST
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable(
                'fun_test_questions'
            )
        ) {
            Schema::table(
                'fun_test_questions',
                function (
                    Blueprint $table
                ): void {
                    if (
                        ! Schema::hasColumn(
                            'fun_test_questions',
                            'department_id'
                        )
                    ) {
                        $table->foreignId(
                            'department_id'
                        )
                            ->nullable()
                            ->constrained(
                                'departments'
                            )
                            ->nullOnDelete();
                    }

                    if (
                        ! Schema::hasColumn(
                            'fun_test_questions',
                            'created_by'
                        )
                    ) {
                        $table->foreignId(
                            'created_by'
                        )
                            ->nullable()
                            ->constrained(
                                'users'
                            )
                            ->nullOnDelete();
                    }

                    if (
                        ! Schema::hasColumn(
                            'fun_test_questions',
                            'title'
                        )
                    ) {
                        $table->string(
                            'title'
                        )->nullable();
                    }
                }
            );
        }
    }

    public function down(): void
    {
        /*
         * Down dibuat konservatif.
         * Jangan gunakan rollback setelah data baru sudah dipakai
         * tanpa backup.
         */

        if (Schema::hasTable('users')) {
            Schema::table(
                'users',
                function (Blueprint $table): void {
                    if (
                        Schema::hasColumn(
                            'users',
                            'position_name'
                        )
                    ) {
                        $table->dropColumn(
                            'position_name'
                        );
                    }
                }
            );
        }

        if (Schema::hasTable('training_modules')) {
            Schema::table(
                'training_modules',
                function (Blueprint $table): void {
                    $columns = [];

                    if (
                        Schema::hasColumn(
                            'training_modules',
                            'material_files'
                        )
                    ) {
                        $columns[] =
                            'material_files';
                    }

                    if (
                        Schema::hasColumn(
                            'training_modules',
                            'material_file_names'
                        )
                    ) {
                        $columns[] =
                            'material_file_names';
                    }

                    if ($columns !== []) {
                        $table->dropColumn(
                            $columns
                        );
                    }
                }
            );
        }

        /*
         * Kolom lainnya sengaja tidak otomatis
         * dihapus untuk mencegah kehilangan data.
         */
    }
};
