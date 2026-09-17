<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;

class weekendBelongsToUserMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $user     = $request->route('worker');
        abort_unless($user instanceof \App\Models\User, 404, 'Пользователь в url не найден');

        $weekend  = $request->route('weekend');
        abort_unless($weekend instanceof \App\Models\UserWeekends, 404, 'Идентификатор отпуска сотрудника в url не найден');

        $isExist  = $user->weekends()->whereKey($weekend->id)->exists();
        abort_unless($isExist, 404, 'Идентификатор отпуска сотрудника не найден');

        return $next($request);
    }
}
