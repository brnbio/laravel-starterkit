<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

final class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureViews();
        $this->configureRateLimiting();
    }

    private function configureViews(): void
    {
        Fortify::loginView(fn(Request $request) => Inertia::render('auth/Login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'status'           => $request->session()->get('status'),
        ]));

        Fortify::twoFactorChallengeView(fn() => inertia('auth/TwoFactorChallenge'));
        Fortify::confirmPasswordView(fn() => inertia('auth/ConfirmPassword'));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function(Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function(Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
