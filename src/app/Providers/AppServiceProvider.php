<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootStrictMode();
        $this->bootDefaults();
    }

    private function bootStrictMode(): void
    {
        Date::use(CarbonImmutable::class);
        Model::shouldBeStrict(app()->isLocal());
        Model::automaticallyEagerLoadRelationships();
    }

    private function bootDefaults(): void
    {
        Number::useLocale('de');
        Number::useCurrency('EUR');
        JsonResource::withoutWrapping();
        Password::defaults(fn() => Password::min(8)->max(48)->mixedCase()->numbers()->symbols());
    }
}
