<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

final class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureViews();
        $this->configureRateLimiting();
    }

    private function configureViews(): void
    {
        Fortify::loginView(fn(Request $request) => inertia('auth/login'));
        Fortify::twoFactorChallengeView(fn() => inertia('auth/two-factor-challenge'));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function(Request $request) {
            $throttleKey = $request->string(User::ATTRIBUTE_EMAIL)->lower()->append('|' . $request->ip())->transliterate()->toString();

            return Limit::perMinute(5)->by($throttleKey);
        });
        RateLimiter::for('two-factor', function(Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
