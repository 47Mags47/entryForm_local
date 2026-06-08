<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeRequest;
use App\Models\Division;
use App\Models\Service;
use App\Models\Subscribe;
use Inertia\Inertia;

class SubscribeController
{
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

    public function store(StoreSubscribeRequest $request, Division $division)
    {
        Subscribe::create(array_merge($request->validated(), ['division_id' => $division->id]));

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
        } elseif (user()->hasRole('division_admin')) {
            if ($subscribe->division_id !== user()->division->id)
                abort(403);
        } else {
            if (!user()->hasRole('admin'))
                abort(403);
        }

        $subscribe->delete();

        return redirect()->route('subscribes.index', ['division' => $division->id]);
    }
}
