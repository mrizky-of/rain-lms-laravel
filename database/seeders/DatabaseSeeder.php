<?php

namespace Database\Seeders;

use App\Models\CompanyContent;
use App\Models\ContributorProfile;
use App\Models\Department;
use App\Models\FunTestQuestion;
use App\Models\Question;
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

        $hr =
            Department::firstOrCreate(
                [
                    'code' => 'HR',
                ],
                [
                    'name' => 'HR / HRGA',

                    'description' => 'Human Resources & General Affairs',

                    'is_active' => true,
                ]
            );

        $finance =
            Department::firstOrCreate(
                [
                    'code' => 'FIN',
                ],
                [
                    'name' => 'Finance',

                    'is_active' => true,
                ]
            );

        $scm =
            Department::firstOrCreate(
                [
                    'code' => 'SCM',
                ],
                [
                    'name' => 'SCM / IT',

                    'is_active' => true,
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $admin =
            User::updateOrCreate(
                [
                    'email' => 'admin@rain.test',
                ],
                [
                    'name' => 'Super Admin RAIN',

                    'password' => 'Admin123!',

                    'role' => User::ROLE_SUPER_ADMIN,

                    'status' => User::STATUS_ACTIVE,

                    'position_name' => 'System Administrator',
                ]
            );

        $hrUser =
            User::updateOrCreate(
                [
                    'email' => 'hr@rain.test',
                ],
                [
                    'name' => 'HR Administrator',

                    'password' => 'Password123!',

                    'department_id' => $hr->id,

                    'position_name' => 'HR Administrator',

                    'role' => User::ROLE_HR,

                    'status' => User::STATUS_ACTIVE,
                ]
            );

        $manager =
            User::updateOrCreate(
                [
                    'email' => 'manager@rain.test',
                ],
                [
                    'name' => 'Manager SCM',

                    'password' => 'Password123!',

                    'department_id' => $scm->id,

                    'position_name' => 'SCM CI Manager',

                    'role' => User::ROLE_MANAGER,

                    'status' => User::STATUS_ACTIVE,
                ]
            );

        $employee =
            User::updateOrCreate(
                [
                    'email' => 'employee@rain.test',
                ],
                [
                    'name' => 'Employee SCM',

                    'password' => 'Password123!',

                    'department_id' => $scm->id,

                    'position_name' => 'SCM Staff',

                    'manager_id' => $manager->id,

                    'role' => User::ROLE_KARYAWAN,

                    'status' => User::STATUS_ACTIVE,
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTOR
        |--------------------------------------------------------------------------
        */

        ContributorProfile::updateOrCreate(
            [
                'user_id' => $manager->id,
            ],
            [
                'nickname' => 'M. Farouq',

                'bio' => 'Contributor konten SCM dan Continuous Improvement.',

                'expertise' => 'SCM, Procurement, Continuous Improvement',

                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MANDATORY MODULE
        |--------------------------------------------------------------------------
        */

        $module =
            TrainingModule::updateOrCreate(
                [
                    'slug' => 'code-of-conduct-2026',
                ],
                [
                    'title' => 'Code of Conduct (CoC) RAIN Group 2026',

                    'description' => 'Pedoman etika dan perilaku perusahaan RAIN Group.',

                    'category' => TrainingModule::CATEGORY_MANDATORY,

                    'duration_minutes' => 20,

                    'kpi_points' => 30,

                    'passing_score' => 75,

                    'is_mandatory' => true,

                    'is_active' => true,

                    'published_at' => now(),

                    'created_by' => $admin->id,

                    'material_files' => [],

                    'material_file_names' => [],
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        $quiz =
            Quiz::updateOrCreate(
                [
                    'training_module_id' => $module->id,

                    'title' => 'Compliance & CoC',
                ],
                [
                    'created_by' => $admin->id,

                    'question_count' => 2,

                    'time_limit_minutes' => 20,

                    'max_attempts' => 3,

                    'is_active' => true,
                ]
            );

        Question::updateOrCreate(
            [
                'quiz_id' => $quiz->id,

                'sort_order' => 1,
            ],
            [
                'type' => Question::TYPE_MULTIPLE_CHOICE,

                'question_text' => 'Apa kepanjangan dari CoC dalam konteks perusahaan RAIN Group?',

                'options' => [
                    'A' => 'Code of Conduct',

                    'B' => 'Charter of Company',

                    'C' => 'Code of Commerce',

                    'D' => 'Contract of Compliance',
                ],

                'correct_answer' => 'A',

                'explanation' => 'CoC merupakan singkatan dari Code of Conduct.',

                'points' => 1,
            ]
        );

        Question::updateOrCreate(
            [
                'quiz_id' => $quiz->id,

                'sort_order' => 2,
            ],
            [
                'type' => Question::TYPE_MULTIPLE_CHOICE,

                'question_text' => 'Manakah tindakan yang sesuai Work Ethics?',

                'options' => [
                    'A' => 'Menggunakan aset perusahaan untuk pribadi',

                    'B' => 'Melaporkan konflik kepentingan',

                    'C' => 'Membagikan data rahasia',

                    'D' => 'Menerima gratifikasi tanpa laporan',
                ],

                'correct_answer' => 'B',

                'explanation' => 'Konflik kepentingan harus dilaporkan melalui mekanisme perusahaan.',

                'points' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | COMPANY INFORMATION
        |--------------------------------------------------------------------------
        */

        CompanyContent::updateOrCreate(
            [
                'key' => 'vision',
            ],
            [
                'title' => 'Visi',

                'content' => 'Menjadi perusahaan energi dan sumber daya alam yang terpercaya, berkelanjutan, dan memberikan nilai terbaik bagi seluruh pemangku kepentingan.',
            ]
        );

        CompanyContent::updateOrCreate(
            [
                'key' => 'mission',
            ],
            [
                'title' => 'Misi',

                'content' => 'Mengelola sumber daya secara profesional, efisien, dan bertanggung jawab.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | FUN TEST SAMPLE
        |--------------------------------------------------------------------------
        */

        FunTestQuestion::updateOrCreate(
            [
                'department_id' => $scm->id,

                'mode' => FunTestQuestion::MODE_TRUE_FALSE,

                'title' => 'SCM Quick Check',
            ],
            [
                'created_by' => $manager->id,

                'prompt' => 'OTIF berarti On Time In Full.',

                'payload' => [],

                'answer' => [
                    'value' => 'true',
                ],

                'explanation' => 'OTIF adalah On Time In Full.',

                'is_active' => true,
            ]
        );
    }
}
