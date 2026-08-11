<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class DivisionMiddleware extends Middleware
{
    public function share(Request $request): array
    {
        $shared = parent::share($request);

        if($request->division !== null)
            $shared['current_division'] = $request->division->toResource();

        return $shared;
    }
}
