<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\CompanyContent;
use App\Models\CompanyPolicy;
use App\Models\ContributorProfile;
use App\Models\Department;
use App\Models\FunTestQuestion;
use App\Models\Lesson;
use App\Models\ManagerReview;
use App\Models\Position;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\Sop;
use App\Models\TrainingModule;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\CompanyContentPolicy;
use App\Policies\CompanyPolicyPolicy;
use App\Policies\ContributorProfilePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\FunTestQuestionPolicy;
use App\Policies\LessonPolicy;
use App\Policies\ManagerReviewPolicy;
use App\Policies\PositionPolicy;
use App\Policies\QuestionOptionPolicy;
use App\Policies\QuestionPolicy;
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
            Position::class,
            PositionPolicy::class
        );

        Gate::policy(
            TrainingModule::class,
            TrainingModulePolicy::class
        );

        Gate::policy(
            Lesson::class,
            LessonPolicy::class
        );

        Gate::policy(
            Quiz::class,
            QuizPolicy::class
        );

        Gate::policy(
            Question::class,
            QuestionPolicy::class
        );

        Gate::policy(
            QuestionOption::class,
            QuestionOptionPolicy::class
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

        Gate::policy(
            ContributorProfile::class,
            ContributorProfilePolicy::class
        );

        Gate::policy(
            FunTestQuestion::class,
            FunTestQuestionPolicy::class
        );

        Gate::policy(
            Assignment::class,
            AssignmentPolicy::class
        );

        Gate::policy(
            ManagerReview::class,
            ManagerReviewPolicy::class
        );
    }
}
