<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Division;
use App\Models\Service;
use App\Models\Subscribe;
use Inertia\Inertia;

use Carbon\CarbonImmutable;

class SubscribeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Division $division)
    {
        return Inertia::render('pages/subscribes/index', [
            'subscribes' => fn() => getResource($division->subscribes()->whereHasAccess()),
            'division' => fn() => getResource($division),
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

    /**
     * Display the specified resource.
     */
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
}
