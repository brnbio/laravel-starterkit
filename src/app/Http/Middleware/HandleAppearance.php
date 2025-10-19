<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class HandleAppearance
{
    public function handle(Request $request, Closure $next): mixed
    {
        view()->share('appearance', $request->cookie('appearance') ?? 'system');

        return $next($request);
    }
}
