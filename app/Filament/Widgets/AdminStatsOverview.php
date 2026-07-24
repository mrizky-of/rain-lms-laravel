<?php

namespace App\Filament\Widgets;

use App\Models\CompanyPolicy;
use App\Models\ContributorProfile;
use App\Models\FunTestQuestion;
use App\Models\Quiz;
use App\Models\Sop;
use App\Models\TrainingModule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort =
        1;

    protected int|string|array $columnSpan =
        'full';

    protected function getStats(): array
    {
        $user =
            auth()->user();

        /*
         * HR Dashboard
         */

        if (
            $user->isHr()
            && ! $user->isContributor()
        ) {
            return [
                Stat::make(
                    'Company Policies',
                    CompanyPolicy::query()
                        ->count()
                ),

                Stat::make(
                    'SOP',
                    Sop::query()
                        ->count()
                ),

                Stat::make(
                    'Active Employees',
                    User::query()
                        ->where(
                            'status',
                            User::STATUS_ACTIVE
                        )
                        ->count()
                ),
            ];
        }

        /*
         * Contributor Dashboard
         */

        if (
            $user->isContributor()
            && ! $user->isSuperAdmin()
        ) {
            $modules =
                TrainingModule::query()
                    ->manageableBy($user)
                    ->count();

            $quizzes =
                Quiz::query()
                    ->manageableBy($user)
                    ->count();

            $funTests =
                FunTestQuestion::query()
                    ->manageableBy($user)
                    ->count();

            $ownUploads =
                $user
                    ->createdTrainingModules()
                    ->count()
                +
                $user
                    ->createdQuizzes()
                    ->count()
                +
                $user
                    ->createdFunTests()
                    ->count();

            return [
                Stat::make(
                    'Module Divisi',
                    $modules
                ),

                Stat::make(
                    'Quiz Divisi',
                    $quizzes
                ),

                Stat::make(
                    'Fun Test Divisi',
                    $funTests
                ),

                Stat::make(
                    'Kontribusi Saya',
                    $ownUploads
                ),
            ];
        }

        /*
         * Super Admin Dashboard
         */

        return [
            Stat::make(
                'Active Users',
                User::query()
                    ->where(
                        'status',
                        User::STATUS_ACTIVE
                    )
                    ->count()
            ),

            Stat::make(
                'Training Modules',
                TrainingModule::query()
                    ->count()
            ),

            Stat::make(
                'Quizzes',
                Quiz::query()
                    ->count()
            ),

            Stat::make(
                'Contributors',
                ContributorProfile::query()
                    ->where(
                        'is_active',
                        true
                    )
                    ->count()
            ),
        ];
    }
}
