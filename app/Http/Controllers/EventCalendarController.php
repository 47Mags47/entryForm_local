<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Service;
use App\Models\Subscribe;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventCalendarController
{
    public function index(Request $request, Division $division)
    {
        $day = $request->has(['year', 'month', 'day'])
            ? CarbonImmutable::create($request->input('year'), $request->input('month'), $request->input('day'))
            : CarbonImmutable::now()->startOfDay();

        $subscribes = Subscribe::divisionSubscribes($division)->whereBetween('start_at', [$day->startOfDay(), $day->endOfDay()])->get();


        return Inertia::render('pages/event-calendar/index', [
            'subscribes' => fn() => $subscribes,
            'division' => fn() => $division->toResource(),
            'dates' => fn() => [
                'previous' => [
                    'day' => $day->subDay()->day,
                    'month' => $day->subDay()->month,
                    'year' => $day->subDay()->year,
                ],
                'current' => [
                    'day' => $day->day,
                    'month' => $day->month,
                    'year' => $day->year,
                ],
                'next' => [
                    'day' => $day->addDay()->day,
                    'month' => $day->addDay()->month,
                    'year' => $day->addDay()->year,
                ],
            ]
        ]);
    }
}
