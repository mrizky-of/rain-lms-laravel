<?php

use App\Livewire\Pages\AssignmentPage;
use App\Livewire\Pages\CompanyPolicyPage;
use App\Livewire\Pages\ContributorPage;
use App\Livewire\Pages\Curriculum;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\FunTestZone;
use App\Livewire\Pages\KpiReport;
use App\Livewire\Pages\ManagerReviewPage;
use App\Livewire\Pages\Profile;
use App\Livewire\Pages\QuizTake;
use App\Livewire\Pages\SopPage;
use App\Livewire\Pages\TrainingModuleShow;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::redirect(
    '/',
    '/dashboard'
)->name('home');

/*
|--------------------------------------------------------------------------
| RAIN LMS PORTAL
|--------------------------------------------------------------------------
|
| Portal utama digunakan oleh:
|
| - Employee / Karyawan
| - Manager
| - HR
|
| Login:
|
| /login
|
| Admin menggunakan login Filament terpisah:
|
| /admin/login
|
*/

Route::middleware('auth')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            Dashboard::class
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            Profile::class
        )->name('profile.index');

        /*
        |--------------------------------------------------------------------------
        | Learning & Assessment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/curriculum',
            Curriculum::class
        )->name('curriculum');

        Route::get(
            '/training/{module:slug}',
            TrainingModuleShow::class
        )->name('training.show');

        Route::get(
            '/quiz/{quiz}',
            QuizTake::class
        )->name('quiz.take');

        Route::get(
            '/assignments',
            AssignmentPage::class
        )->name('assignments');

        Route::get(
            '/fun-test',
            FunTestZone::class
        )->name('fun-test');

        /*
        |--------------------------------------------------------------------------
        | Informasi & Regulasi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/company-policies',
            CompanyPolicyPage::class
        )->name('company-policies');

        Route::get(
            '/sop',
            SopPage::class
        )->name('sop');

        /*
        |--------------------------------------------------------------------------
        | Performa & Review
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/kpi-report',
            KpiReport::class
        )->name('kpi-report');

        Route::get(
            '/manager-review',
            ManagerReviewPage::class
        )->name('manager-review');

        Route::get(
            '/contributors',
            ContributorPage::class
        )->name('contributors');
    });

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/

require __DIR__ . '/settings.php';