<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Default Settings
        |--------------------------------------------------------------------------
        */

        Route::redirect(
            '/settings',
            '/settings/profile'
        );

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::livewire(
            'settings/profile',
            'pages::settings.profile'
        )->name('profile.edit');

        /*
        |--------------------------------------------------------------------------
        | Appearance
        |--------------------------------------------------------------------------
        */

        Route::livewire(
            'settings/appearance',
            'pages::settings.appearance'
        )->name('appearance.edit');

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        Route::livewire(
            'settings/security',
            'pages::settings.security'
        )
            ->middleware(
                'password.confirm'
            )
            ->name(
                'security.edit'
            );
    });
