<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Assignments\AssignmentResource;
use App\Filament\Resources\CompanyContents\CompanyContentResource;
use App\Filament\Resources\CompanyPolicies\CompanyPolicyResource;
use App\Filament\Resources\ContributorProfiles\ContributorProfileResource;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\FunTestQuestions\FunTestQuestionResource;
use App\Filament\Resources\Lessons\LessonResource;
use App\Filament\Resources\ManagerReviews\ManagerReviewResource;
use App\Filament\Resources\Positions\PositionResource;
use App\Filament\Resources\QuestionOptions\QuestionOptionResource;
use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\Quizzes\QuizResource;
use App\Filament\Resources\Sops\SopResource;
use App\Filament\Resources\TrainingModules\TrainingModuleResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\RecentAssignments;
use App\Filament\Widgets\TrainingCompletionChart;
use App\Filament\Widgets\UsersByDepartmentChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(
        Panel $panel
    ): Panel {
        return $panel

            /*
            |--------------------------------------------------------------------------
            | Panel
            |--------------------------------------------------------------------------
            */

            ->default()

            ->id('admin')

            ->path('admin')

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            ->login()

            /*
            |--------------------------------------------------------------------------
            | Branding
            |--------------------------------------------------------------------------
            */

            ->brandName(
                'RAIN LMS Administration'
            )

            /*
             * Logo yang sama dengan halaman login portal.
             *
             * Pastikan file tersedia:
             *
             * public/images/rain-group-logo.png
             */

            ->brandLogo(
                asset(
                    'images/rain-group-logo.png'
                )
            )

            ->brandLogoHeight(
                '2.5rem'
            )

            /*
            |--------------------------------------------------------------------------
            | Colors
            |--------------------------------------------------------------------------
            */

            ->colors([

                'primary' =>
                    Color::Green,

                'success' =>
                    Color::Emerald,

                'info' =>
                    Color::Sky,

                'warning' =>
                    Color::Amber,

                'danger' =>
                    Color::Rose,

            ])

            /*
            |--------------------------------------------------------------------------
            | Layout
            |--------------------------------------------------------------------------
            */

            ->maxContentWidth(
                Width::Full
            )

            /*
             * Sidebar bisa diciutkan di desktop.
             */

            ->sidebarCollapsibleOnDesktop()

            /*
             * Warning bila admin menutup form
             * yang punya perubahan belum disimpan.
             */

            ->unsavedChangesAlerts()

            /*
            |--------------------------------------------------------------------------
            | Resources
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in:
                    app_path(
                        'Filament/Resources'
                    ),

                for:
                    'App\\Filament\\Resources'
            )

            /*
            |--------------------------------------------------------------------------
            | Pages
            |--------------------------------------------------------------------------
            */

            ->pages([
                Dashboard::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Dashboard Widgets
            |--------------------------------------------------------------------------
            |
            | Kita register eksplisit supaya dashboard hanya berisi
            | widget RAIN LMS yang kita buat.
            |
            */

            ->widgets([

                AdminStatsOverview::class,

                TrainingCompletionChart::class,

                UsersByDepartmentChart::class,

                RecentAssignments::class,

            ])

            /*
            |--------------------------------------------------------------------------
            | Navigation
            |--------------------------------------------------------------------------
            |
            | Semua sidebar dikelompokkan dari sini.
            |
            | Tidak perlu menambahkan $navigationGroup
            | ke 15 Resource satu per satu.
            |
            */

            ->navigation(
                function (
                    NavigationBuilder $builder
                ): NavigationBuilder {

                    return $builder
                        ->groups([

                            /*
                            |--------------------------------------------------------------------------
                            | Overview
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Overview'
                            )
                                ->items([

                                    ...Dashboard::getNavigationItems(),

                                ])
                                ->collapsible(
                                    false
                                ),

                            /*
                            |--------------------------------------------------------------------------
                            | Master Data
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Master Data'
                            )
                                ->items([

                                    ...DepartmentResource::getNavigationItems(),

                                    ...PositionResource::getNavigationItems(),

                                    ...UserResource::getNavigationItems(),

                                ]),

                            /*
                            |--------------------------------------------------------------------------
                            | Learning Management
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Learning Management'
                            )
                                ->items([

                                    ...TrainingModuleResource::getNavigationItems(),

                                    ...LessonResource::getNavigationItems(),

                                    ...QuizResource::getNavigationItems(),

                                    ...QuestionResource::getNavigationItems(),

                                    ...QuestionOptionResource::getNavigationItems(),

                                ]),

                            /*
                            |--------------------------------------------------------------------------
                            | Performance & Review
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Performance & Review'
                            )
                                ->items([

                                    ...AssignmentResource::getNavigationItems(),

                                    ...ManagerReviewResource::getNavigationItems(),

                                ])
                                ->collapsed(),

                            /*
                            |--------------------------------------------------------------------------
                            | Informasi & Regulasi
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Informasi & Regulasi'
                            )
                                ->items([

                                    ...CompanyContentResource::getNavigationItems(),

                                    ...CompanyPolicyResource::getNavigationItems(),

                                    ...SopResource::getNavigationItems(),

                                ])
                                ->collapsed(),

                            /*
                            |--------------------------------------------------------------------------
                            | Engagement
                            |--------------------------------------------------------------------------
                            */

                            NavigationGroup::make(
                                'Engagement'
                            )
                                ->items([

                                    ...ContributorProfileResource::getNavigationItems(),

                                    ...FunTestQuestionResource::getNavigationItems(),

                                ])
                                ->collapsed(),

                        ]);
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Middleware
            |--------------------------------------------------------------------------
            */

            ->middleware([

                EncryptCookies::class,

                AddQueuedCookiesToResponse::class,

                StartSession::class,

                AuthenticateSession::class,

                ShareErrorsFromSession::class,

                VerifyCsrfToken::class,

                SubstituteBindings::class,

                DisableBladeIconComponents::class,

                DispatchServingFilamentEvent::class,

            ])

            /*
            |--------------------------------------------------------------------------
            | Authentication Middleware
            |--------------------------------------------------------------------------
            */

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}