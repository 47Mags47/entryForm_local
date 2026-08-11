<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Middleware;

class DivisionAccessMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        $division = $request->route('division');
        abort_unless($division instanceof \App\Models\Division, 404, 'Подразделение в url не найдено');

        $hasAccess = user()->divisions()->whereKey($division->id)->exists() || user()->hasRole('admin');
        abort_unless($hasAccess, 403);

        return $next($request);
    }
}
