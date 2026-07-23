<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');

            $table->string('level')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')
                ->nullable()
                ->unique()
                ->after('id');

            $table->string('phone')
                ->nullable()
                ->after('email');

            $table->string('avatar')
                ->nullable();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('position_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('manager_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('role')
                ->default('karyawan');

            $table->string('status')
                ->default('active');

            $table->unsignedTinyInteger('semester')
                ->default(1);

            $table->unsignedInteger('annual_leave_quota')
                ->default(12);

            $table->date('join_date')
                ->nullable();

            $table->index([
                'department_id',
                'role',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['manager_id']);

            $table->dropColumn([
                'nik',
                'phone',
                'avatar',
                'department_id',
                'position_id',
                'manager_id',
                'role',
                'status',
                'semester',
                'annual_leave_quota',
                'join_date',
            ]);
        });

        Schema::dropIfExists('positions');

        Schema::dropIfExists('departments');
    }
};
