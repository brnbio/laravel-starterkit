<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class HandleAppearance
{
    public function handle(Request $request, Closure $next): Response
    {
        view()->share('appearance', $request->cookie('appearance') ?? 'system');

        return $next($request);
    }
}
