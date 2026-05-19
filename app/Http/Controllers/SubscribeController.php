<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeRequest;
use App\Http\Requests\UpdateSubscribeRequest;
use App\Http\Resources\SubscribeResource;
use App\Models\Division;
use App\Models\Subscribe;
use Inertia\Inertia;

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
