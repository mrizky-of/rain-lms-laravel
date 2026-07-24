<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->configureActions();

        $this->configureViews();

        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(
            ResetUserPassword::class
        );
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        |
        | Login portal:
        |
        | http://rain-lms.test/login
        |
        */

        Fortify::loginView(
            fn () => view(
                'pages::auth.login'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Password Confirmation
        |--------------------------------------------------------------------------
        */

        Fortify::confirmPasswordView(
            fn () => view(
                'pages::auth.confirm-password'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Forgot Password
        |--------------------------------------------------------------------------
        */

        Fortify::requestPasswordResetLinkView(
            fn () => view(
                'pages::auth.forgot-password'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */

        Fortify::resetPasswordView(
            fn () => view(
                'pages::auth.reset-password'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Two Factor Challenge
        |--------------------------------------------------------------------------
        |
        | Fitur ini boleh tetap terdaftar walaupun 2FA belum diaktifkan.
        |
        */

        Fortify::twoFactorChallengeView(
            fn () => view(
                'pages::auth.two-factor-challenge'
            )
        );
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Login Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for(
            'login',
            function (
                Request $request
            ): Limit {

                $throttleKey =
                    Str::transliterate(
                        Str::lower(
                            (string)
                            $request->input(
                                Fortify::username()
                            )
                        )
                        .'|'
                        .$request->ip()
                    );

                return Limit::perMinute(
                    5
                )->by(
                    $throttleKey
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Two Factor Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for(
            'two-factor',
            function (
                Request $request
            ): Limit {

                return Limit::perMinute(
                    5
                )->by(
                    (string)
                    $request
                        ->session()
                        ->get(
                            'login.id',
                            $request->ip()
                        )
                );
            }
        );
    }
}
