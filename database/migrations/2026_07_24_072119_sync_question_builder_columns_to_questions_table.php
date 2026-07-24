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
        | QUESTIONS TABLE
        |--------------------------------------------------------------------------
        |
        | Menyinkronkan schema lama Questions dengan arsitektur Question Builder.
        |
        | Multiple Choice:
        | - options JSON
        | - correct_answer
        |
        | Essay:
        | - essay_answer_key
        |
        | Semua tipe:
        | - explanation
        |
        */

        if (! Schema::hasTable('questions')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | TYPE
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'type')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->string('type')
                    ->default('multiple_choice');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | OPTIONS
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'options')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->json('options')
                    ->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | CORRECT ANSWER
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'correct_answer')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->string('correct_answer', 10)
                    ->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ESSAY ANSWER KEY
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'essay_answer_key')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->longText('essay_answer_key')
                    ->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | EXPLANATION
        |--------------------------------------------------------------------------
        |
        | Ini kolom yang menyebabkan error sekarang:
        |
        | Unknown column 'explanation' in 'field list'
        |
        */

        if (! Schema::hasColumn('questions', 'explanation')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->longText('explanation')
                    ->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | POINTS
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'points')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->decimal('points', 8, 2)
                    ->default(1);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORT ORDER
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('questions', 'sort_order')) {
            Schema::table('questions', function (Blueprint $table): void {
                $table->unsignedInteger('sort_order')
                    ->default(1);
            });
        }
    }

    public function down(): void
    {
        /*
         * Sengaja tidak menghapus kolom secara otomatis.
         *
         * Kolom-kolom ini bisa sudah berisi Question baru.
         * Rollback destructive berisiko kehilangan data.
         */
    }
};