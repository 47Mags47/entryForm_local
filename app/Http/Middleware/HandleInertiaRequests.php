<?php

namespace App\Http\Middleware;

use App\Http\Resources\CurrentUserResource;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $shared = parent::share($request);

        if (in_array('auth', $request->route()->middleware()))
            $shared['current_user'] = CurrentUserResource::make(user());

        // Добавляет flash уведомления к ответу
        if (!in_array('shared', $shared))
            $shared['flash'] = [];

        if ($request->session()->has('success'))
            $shared['flash']['success'][] = $request->session()->get('success');

        if ($request->session()->has('error'))
            $shared['flash']['error'][] = $request->session()->get('error');

        if ($request->session()->has('info'))
            $shared['flash']['info'][] = $request->session()->get('info');

        if ($request->session()->has('warning'))
            $shared['flash']['warning'][] = $request->session()->get('warning');

        if ($request->session()->has('loading'))
            $shared['flash']['loading'][] = $request->session()->get('loading');

        return $shared;
    }
}
