<?php

namespace App\Http\Controllers;

use App\Models\Division;
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

        // HACK чтоб сотрудник мог видеть записи свои и чужие, если услуга совпадает
        $workers = user()->hasRole('division_worker')
            ? collect([user()])
            : $division->admins->merge($division->workers);

        $subscribes = $workers
            ->filter(fn ($worker) => $worker->subscribes()->exists())
            ->map(fn ($worker) => [
                'worker' => getResource($worker),
                'timeline' => $worker->getTimeLine($day, $division),
            ]);



        return Inertia::render('pages/event-calendar/index', [
            'subscribes' => fn() => $subscribes,
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
