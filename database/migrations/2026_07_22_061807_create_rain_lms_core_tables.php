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
        | TRAINING MODULES
        |--------------------------------------------------------------------------
        */

        Schema::create('training_modules', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description')
                ->nullable();

            $table->string('thumbnail')
                ->nullable();

            $table->string('category')
                ->default('mandatory');

            $table->unsignedInteger('duration_minutes')
                ->default(0);

            $table->unsignedInteger('kpi_points')
                ->default(0);

            $table->decimal('passing_score', 5, 2)
                ->default(70);

            $table->boolean('is_mandatory')
                ->default(false);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamp('published_at')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | MODULE TARGET DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        Schema::create('department_training_module', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                [
                    'department_id',
                    'training_module_id',
                ],
                'dept_training_module_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | LESSONS
        |--------------------------------------------------------------------------
        */

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->string('type')
                ->default('video');

            $table->string('video_url')
                ->nullable();

            $table->string('file_path')
                ->nullable();

            $table->longText('content')
                ->nullable();

            $table->unsignedInteger('duration_minutes')
                ->default(0);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | ENROLLMENTS
        |--------------------------------------------------------------------------
        */

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->string('source')
                ->default('auto');

            $table->string('status')
                ->default('not_started');

            $table->decimal('progress_percentage', 5, 2)
                ->default(0);

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('due_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'user_id',
                    'training_module_id',
                ],
                'user_training_enrollment_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | LESSON PROGRESS
        |--------------------------------------------------------------------------
        */

        Schema::create('lesson_progresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('lesson_id')
                ->constrained('lessons')
                ->cascadeOnDelete();

            $table->boolean('is_completed')
                ->default(false);

            $table->timestamp('completed_at')
                ->nullable();

            $table->unsignedInteger('last_position_seconds')
                ->default(0);

            $table->timestamps();

            $table->unique(
                [
                    'user_id',
                    'lesson_id',
                ],
                'user_lesson_progress_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | QUIZZES
        |--------------------------------------------------------------------------
        */

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->string('title');

            $table->unsignedInteger('time_limit_minutes')
                ->default(30);

            $table->unsignedInteger('max_attempts')
                ->default(3);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->text('question_text');

            $table->string('type')
                ->default('multiple_choice');

            $table->unsignedInteger('points')
                ->default(1);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->text('option_text');

            $table->boolean('is_correct')
                ->default(false);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | QUIZ ATTEMPTS
        |--------------------------------------------------------------------------
        */

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('attempt_number');

            $table->decimal('score', 5, 2)
                ->default(0);

            $table->boolean('passed')
                ->default(false);

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'quiz_id',
                    'user_id',
                    'attempt_number',
                ],
                'quiz_user_attempt_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | QUIZ ANSWERS
        |--------------------------------------------------------------------------
        */

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_attempt_id')
                ->constrained('quiz_attempts')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->foreignId('question_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete();

            $table->boolean('is_correct')
                ->default(false);

            $table->unsignedInteger('points_earned')
                ->default(0);

            $table->timestamps();

            $table->unique(
                [
                    'quiz_attempt_id',
                    'question_id',
                ],
                'quiz_attempt_question_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | ASSIGNMENTS
        |--------------------------------------------------------------------------
        */

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manager_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->text('notes')
                ->nullable();

            $table->string('status')
                ->default('assigned');

            $table->timestamp('due_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | KPI LEDGER
        |--------------------------------------------------------------------------
        */

        Schema::create('kpi_ledgers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->nullable()
                ->constrained('training_modules')
                ->nullOnDelete();

            $table->integer('points');

            $table->string('source');

            $table->text('description')
                ->nullable();

            $table->timestamp('earned_at');

            $table->timestamps();

            $table->unique(
                [
                    'user_id',
                    'training_module_id',
                    'source',
                ],
                'user_module_kpi_source_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | CERTIFICATES
        |--------------------------------------------------------------------------
        */

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->string('certificate_number')
                ->unique();

            $table->string('file_path')
                ->nullable();

            $table->timestamp('issued_at');

            $table->timestamps();

            $table->unique(
                [
                    'user_id',
                    'training_module_id',
                ],
                'user_module_certificate_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | COMPANY CONTENT
        |--------------------------------------------------------------------------
        */

        Schema::create('company_contents', function (Blueprint $table) {
            $table->id();

            $table->string('key')
                ->unique();

            $table->string('title');

            $table->longText('content');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | COMPANY POLICIES
        |--------------------------------------------------------------------------
        */

        Schema::create('company_policies', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->string('slug')
                ->unique();

            $table->text('summary')
                ->nullable();

            $table->longText('content');

            $table->string('legal_reference')
                ->nullable();

            $table->string('category')
                ->nullable();

            $table->string('target')
                ->default('all');

            $table->string('version')
                ->default('1.0');

            $table->date('effective_date')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        Schema::create('policy_acknowledgements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_policy_id')
                ->constrained('company_policies')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('acknowledged_at');

            $table->timestamps();

            $table->unique(
                [
                    'company_policy_id',
                    'user_id',
                ],
                'policy_user_ack_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | SOP
        |--------------------------------------------------------------------------
        */

        Schema::create('sops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->string('code')
                ->unique();

            $table->string('title');

            $table->string('version')
                ->default('1.0');

            $table->date('effective_date')
                ->nullable();

            $table->string('owner')
                ->nullable();

            $table->text('objective')
                ->nullable();

            $table->longText('procedure')
                ->nullable();

            $table->longText('parameters')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | MANAGER REVIEWS
        |--------------------------------------------------------------------------
        */

        Schema::create('manager_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manager_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('period');

            $table->decimal('score', 5, 2)
                ->nullable();

            $table->string('status')
                ->default('review');

            $table->text('notes')
                ->nullable();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTOR PROFILES
        |--------------------------------------------------------------------------
        */

        Schema::create('contributor_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unique(
                'user_id',
                'contributor_profile_user_unique'
            );

            $table->string('nickname')
                ->nullable();

            $table->text('bio')
                ->nullable();

            $table->text('expertise')
                ->nullable();

            $table->decimal('rating', 5, 2)
                ->default(0);

            $table->timestamps();
        });

        Schema::create('contributor_training_module', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contributor_profile_id')
                ->constrained('contributor_profiles')
                ->cascadeOnDelete();

            $table->foreignId('training_module_id')
                ->constrained('training_modules')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                [
                    'contributor_profile_id',
                    'training_module_id',
                ],
                'contributor_module_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | FUN TEST
        |--------------------------------------------------------------------------
        */

        Schema::create('fun_test_questions', function (Blueprint $table) {
            $table->id();

            $table->string('mode');

            $table->text('prompt');

            $table->json('payload')
                ->nullable();

            $table->json('answer')
                ->nullable();

            $table->text('explanation')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fun_test_questions');

        Schema::dropIfExists(
            'contributor_training_module'
        );

        Schema::dropIfExists(
            'contributor_profiles'
        );

        Schema::dropIfExists(
            'manager_reviews'
        );

        Schema::dropIfExists('sops');

        Schema::dropIfExists(
            'policy_acknowledgements'
        );

        Schema::dropIfExists(
            'company_policies'
        );

        Schema::dropIfExists(
            'company_contents'
        );

        Schema::dropIfExists(
            'certificates'
        );

        Schema::dropIfExists(
            'kpi_ledgers'
        );

        Schema::dropIfExists(
            'assignments'
        );

        Schema::dropIfExists(
            'quiz_answers'
        );

        Schema::dropIfExists(
            'quiz_attempts'
        );

        Schema::dropIfExists(
            'question_options'
        );

        Schema::dropIfExists(
            'questions'
        );

        Schema::dropIfExists(
            'quizzes'
        );

        Schema::dropIfExists(
            'lesson_progresses'
        );

        Schema::dropIfExists(
            'enrollments'
        );

        Schema::dropIfExists(
            'lessons'
        );

        Schema::dropIfExists(
            'department_training_module'
        );

        Schema::dropIfExists(
            'training_modules'
        );
    }
};
