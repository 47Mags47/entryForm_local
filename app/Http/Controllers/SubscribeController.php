<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreSubscribeRequest;
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
            ? Carbon::parse($request->input('from'))
            : now()->startOfMonth()->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))
            : now()->endOfMonth()->endOfDay();

        $query = $division->subscribes()
            ->whereHasAccess()
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
            'workers' => $division->workers->toResourceCollection(),
        ]);
    }

    public function store(StoreSubscribeRequest $request, Division $division){
        $data = $request->validated();

        $data['division_id'] = $division->id;

        $start_date = CarbonImmutable::parse($request->input('start_date'))->format('Y-m-d');
        $start_time = CarbonImmutable::parse($request->input('start_time'))->format('H:i');

        $data['start_at'] = $start_date . ' ' . $start_time;

        Subscribe::create($data);

        return redirect()->route('subscribes.index', ['division' => $division->id]);
    }

    public function show(Division $division, Subscribe $subscribe)
    {
        if (
            $subscribe->division_id !== $division->id
            || (user()->hasRole('division_admin') && $subscribe->division_id !== user()->division->id)
            || (user()->hasRole('division_worker') && $subscribe->worker_id !== user()->id)
        ) {
            abort(403);
        }

        return Inertia::render('pages/subscribes/show', [
            'subscribe' => fn() => getResource($subscribe),
            'division' => fn() => getResource($division),
        ]);
    }

    public function destroy(Division $division, Subscribe $subscribe)
    {
        if (user()->hasRole('division_worker')) {
            if ($subscribe->worker_id !== user()->id)
                abort(403);

            $subscribe->delete();
        }

        elseif (user()->hasRole('division_admin')) {
            if ($subscribe->division_id !== user()->division->id)
                abort(403);

            $subscribe->forceDelete();
        }

        else {
            if (!user()->hasRole('admin'))
                abort(403);

            $subscribe->forceDelete();
        }

        return redirect()->back();
    }
}
