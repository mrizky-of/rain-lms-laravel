<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\FunTestBuilder;
use App\Filament\Pages\QuestionBuilder;
use App\Filament\Resources\CompanyContents\CompanyContentResource;
use App\Filament\Resources\CompanyPolicies\CompanyPolicyResource;
use App\Filament\Resources\ContributorProfiles\ContributorProfileResource;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\Quizzes\QuizResource;
use App\Filament\Resources\Sops\SopResource;
use App\Filament\Resources\TrainingModules\TrainingModuleResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\RecentTrainingModules;
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

            ->default()

            ->id('admin')

            ->path('admin')

            ->login()

            ->brandName(
                'RAIN LMS Administration'
            )

            ->brandLogo(
                asset(
                    'images/rain-group-logo.png'
                )
            )

            ->brandLogoHeight(
                '2.5rem'
            )

            ->colors([
                'primary' => Color::Green,

                'success' => Color::Emerald,

                'info' => Color::Sky,

                'warning' => Color::Amber,

                'danger' => Color::Rose,
            ])

            ->maxContentWidth(
                Width::Full
            )

            ->sidebarCollapsibleOnDesktop()

            ->unsavedChangesAlerts()

            /*
            |--------------------------------------------------------------------------
            | ONLY REGISTER ACTIVE RESOURCES
            |--------------------------------------------------------------------------
            */

            ->resources([
                DepartmentResource::class,
                UserResource::class,
                ContributorProfileResource::class,

                TrainingModuleResource::class,
                QuizResource::class,

                CompanyContentResource::class,
                CompanyPolicyResource::class,
                SopResource::class,
            ])

            ->pages([
                Dashboard::class,

                QuestionBuilder::class,

                FunTestBuilder::class,
            ])

            ->widgets([
                AdminStatsOverview::class,

                UsersByDepartmentChart::class,

                RecentTrainingModules::class,
            ])

            ->navigation(
                function (
                    NavigationBuilder $builder
                ): NavigationBuilder {
                    return $builder
                        ->groups([

                            NavigationGroup::make(
                                'Overview'
                            )
                                ->items([
                                    ...Dashboard::getNavigationItems(),
                                ])
                                ->collapsible(false),

                            NavigationGroup::make(
                                'Master Data'
                            )
                                ->items([
                                    ...DepartmentResource::getNavigationItems(),

                                    ...UserResource::getNavigationItems(),

                                    ...ContributorProfileResource::getNavigationItems(),
                                ]),

                            NavigationGroup::make(
                                'Learning Management'
                            )
                                ->items([
                                    ...TrainingModuleResource::getNavigationItems(),

                                    ...QuizResource::getNavigationItems(),

                                    ...QuestionBuilder::getNavigationItems(),

                                    ...FunTestBuilder::getNavigationItems(),
                                ]),

                            NavigationGroup::make(
                                'Informasi & Regulasi'
                            )
                                ->items([
                                    ...CompanyContentResource::getNavigationItems(),

                                    ...CompanyPolicyResource::getNavigationItems(),

                                    ...SopResource::getNavigationItems(),
                                ]),
                        ]);
                }
            )

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

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
