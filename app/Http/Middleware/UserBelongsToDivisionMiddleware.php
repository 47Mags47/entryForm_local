<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;

class userBelongsToDivisionMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $division = $request->route('division');
        abort_unless($division instanceof \App\Models\Division, 404, 'Подразделение в url не найдено');

        $user     = $request->route('worker');
        abort_unless($user instanceof \App\Models\User, 404, 'Пользователь в url не найден');

        $isExist  = $user->divisions()->whereKey($division->id)->exists();
        abort_unless($isExist, 404, 'Пользователь в подразделении не найден');

        return $next($request);
    }
}
