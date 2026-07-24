<?php

namespace App\Providers;

use App\Models\CompanyContent;
use App\Models\CompanyPolicy;
use App\Models\ContributorProfile;
use App\Models\Department;
use App\Models\Quiz;
use App\Models\Sop;
use App\Models\TrainingModule;
use App\Models\User;
use App\Policies\CompanyContentPolicy;
use App\Policies\CompanyPolicyPolicy;
use App\Policies\ContributorProfilePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\QuizPolicy;
use App\Policies\SopPolicy;
use App\Policies\TrainingModulePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(
            User::class,
            UserPolicy::class
        );

        Gate::policy(
            Department::class,
            DepartmentPolicy::class
        );

        Gate::policy(
            ContributorProfile::class,
            ContributorProfilePolicy::class
        );

        Gate::policy(
            TrainingModule::class,
            TrainingModulePolicy::class
        );

        Gate::policy(
            Quiz::class,
            QuizPolicy::class
        );

        Gate::policy(
            CompanyContent::class,
            CompanyContentPolicy::class
        );

        Gate::policy(
            CompanyPolicy::class,
            CompanyPolicyPolicy::class
        );

        Gate::policy(
            Sop::class,
            SopPolicy::class
        );
    }
}
