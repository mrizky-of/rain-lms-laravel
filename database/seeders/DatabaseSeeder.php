<?php

namespace Database\Seeders;

use App\Models\CompanyContent;
use App\Models\Department;
use App\Models\Position;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        $hr = Department::create([
            'code' => 'HR',
            'name' => 'Human Resources',
            'is_active' => true,
        ]);

        $finance = Department::create([
            'code' => 'FIN',
            'name' => 'Finance',
            'is_active' => true,
        ]);

        $scm = Department::create([
            'code' => 'SCM',
            'name' => 'SCM & Procurement',
            'is_active' => true,
        ]);

        $it = Department::create([
            'code' => 'IT',
            'name' => 'Information Technology',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | POSITIONS
        |--------------------------------------------------------------------------
        */

        $hrManagerPosition =
            Position::create([
                'department_id' => $hr->id,

                'name' => 'HR Manager',

                'level' => 'Manager',
            ]);

        $staffPosition =
            Position::create([
                'department_id' => $finance->id,

                'name' => 'Finance Staff',

                'level' => 'Staff',
            ]);

        $managerPosition =
            Position::create([
                'department_id' => $finance->id,

                'name' => 'Finance Manager',

                'level' => 'Manager',
            ]);

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        User::create([
            'nik' => 'ADMIN001',

            'name' => 'Super Admin RAIN',

            'email' => 'admin@rain.test',

            'password' => 'Admin123!',

            'role' => User::ROLE_SUPER_ADMIN,

            'status' => User::STATUS_ACTIVE,
        ]);

        User::create([
            'nik' => 'HR001',

            'name' => 'HR Administrator',

            'email' => 'hr@rain.test',

            'password' => 'Password123!',

            'department_id' => $hr->id,

            'position_id' => $hrManagerPosition->id,

            'role' => User::ROLE_HR,

            'status' => User::STATUS_ACTIVE,
        ]);

        $financeManager =
            User::create([
                'nik' => 'MGR001',

                'name' => 'Manager Finance',

                'email' => 'manager@rain.test',

                'password' => 'Password123!',

                'department_id' => $finance->id,

                'position_id' => $managerPosition->id,

                'role' => User::ROLE_MANAGER,

                'status' => User::STATUS_ACTIVE,
            ]);

        User::create([
            'nik' => 'EMP001',

            'name' => 'Karyawan Finance',

            'email' => 'employee@rain.test',

            'password' => 'Password123!',

            'department_id' => $finance->id,

            'position_id' => $staffPosition->id,

            'manager_id' => $financeManager->id,

            'role' => User::ROLE_KARYAWAN,

            'status' => User::STATUS_ACTIVE,
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRAINING MODULE
        |--------------------------------------------------------------------------
        */

        $module =
            TrainingModule::create([
                'title' => 'Code of Conduct RAIN Group',

                'slug' => 'code-of-conduct-rain-group',

                'description' => 'Pelatihan wajib mengenai etika, integritas, dan kode perilaku perusahaan.',

                'category' => 'mandatory',

                'duration_minutes' => 60,

                'kpi_points' => 30,

                'passing_score' => 70,

                'is_mandatory' => true,

                'is_active' => true,

                'published_at' => now(),
            ]);

        $module->lessons()->create([
            'title' => 'Pengenalan Code of Conduct',

            'type' => 'video',

            'content' => 'Materi pengenalan kode etik perusahaan.',

            'duration_minutes' => 20,

            'sort_order' => 1,
        ]);

        $module->lessons()->create([
            'title' => 'Integritas dan Profesionalisme',

            'type' => 'article',

            'content' => 'Materi mengenai integritas dan profesionalisme.',

            'duration_minutes' => 20,

            'sort_order' => 2,
        ]);

        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        $quiz =
            Quiz::create([
                'training_module_id' => $module->id,

                'title' => 'Test Code of Conduct',

                'time_limit_minutes' => 30,

                'max_attempts' => 3,

                'is_active' => true,
            ]);

        $question =
            Question::create([
                'quiz_id' => $quiz->id,

                'question_text' => 'Apa nilai utama yang harus dijaga seluruh karyawan?',

                'points' => 10,

                'sort_order' => 1,
            ]);

        QuestionOption::create([
            'question_id' => $question->id,

            'option_text' => 'Integritas',

            'is_correct' => true,
        ]);

        QuestionOption::create([
            'question_id' => $question->id,

            'option_text' => 'Mengabaikan peraturan',

            'is_correct' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | COMPANY CONTENT
        |--------------------------------------------------------------------------
        */

        CompanyContent::create([
            'key' => 'vision',

            'title' => 'Visi Perusahaan',

            'content' => 'Menjadi perusahaan yang tumbuh berkelanjutan dan memberikan nilai terbaik bagi seluruh stakeholder.',
        ]);

        CompanyContent::create([
            'key' => 'mission',

            'title' => 'Misi Perusahaan',

            'content' => 'Membangun bisnis berkelanjutan melalui profesionalisme, integritas, teamwork, dan sustainability.',
        ]);
    }
}
