<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreSubscribeRequest;
use App\Jobs\SendSubscribeWorkerAlertJob;
use App\Models\Division;
use App\Models\Service;
use App\Models\Subscribe;

use Inertia\Inertia;

use Carbon\CarbonImmutable;
use Carbon\Carbon;

class SubscribeController
{
    public function index(Request $request, Division $division)
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d']
        ]);

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : $from->copy()->endOfMonth()->endOfDay();

        $query = $division->subscribes()
            ->orderBy('start_at')
            ->when(
                user()->hasRole('division_worker'),
                fn($query) => $query->withoutTrashed(),
                fn($query) => $query->withTrashed()
            )
            ->whereBetween('start_at', [$from, $to]);

        return Inertia::render('pages/subscribes/index', [
            'subscribes' => fn() => getResource($query),
            'division' => fn() => getResource($division),
            'filters' => $request->only('from', 'to'),
        ]);
    }

    public function create(Division $division)
    {
        return Inertia::render('pages/subscribes/create', [
            'division' => getResource($division),
            'services' => Service::all()->toResourceCollection(),
            'workers' => $division->admins()
                ->wherePivot('is_subscribe_available', true)
                ->whereHas('shedules')
                ->get()
                ->merge(
                    $division->workers()
                        ->wherePivot('is_subscribe_available', true)
                        ->whereHas('shedules')
                        ->get()
                )
                ->toResourceCollection(),
        ]);
    }

    public function store(StoreSubscribeRequest $request, Division $division)
    {
        $data = $request->validated();

        $data['division_id'] = $division->id;

        $start_date = CarbonImmutable::parse($request->input('start_date'))->format('Y-m-d');
        $start_time = CarbonImmutable::parse($request->input('start_time'))->format('H:i');

        $data['start_at'] = $start_date . ' ' . $start_time;

        $subscribe = Subscribe::create($data);

        if($subscribe->worker->receiveMail){
            SendSubscribeWorkerAlertJob::dispatch($subscribe);
        }

        return redirect()->route('subscribes.index', ['division' => $division->id]);
    }

    public function show(Division $division, Subscribe $subscribe)
    {
        if (
            $subscribe->division_id === $division->id       ||
            user()->hasRole('division_admin', $division)    ||
            user()->hasRole('admin')                        ||
            (user()->hasRole('division_worker', $division) && $subscribe->worker_id === user()->id)
        ) {
            return Inertia::render('pages/subscribes/show', [
                'subscribe' => fn() => getResource($subscribe),
            ]);
        } else
            return abort(403);
    }

    public function destroy(Division $division, Subscribe $subscribe)
    {
        if (user()->hasRole('division_worker', $division)) {
            if ($subscribe->worker_id !== user()->id)
                abort(403);

            $subscribe->delete();
        } elseif (user()->hasRole('division_admin', $division)) {
            if ($division->id !== $subscribe->division_id)
                abort(403);

            $subscribe->forceDelete();
        } else {
            if (!user()->hasRole('admin'))
                abort(403);

            $subscribe->forceDelete();
        }

        return redirect()->back();
    }
}
