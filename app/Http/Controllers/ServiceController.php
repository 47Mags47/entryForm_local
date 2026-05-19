<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Http\Resources\ServiceResource;

use Inertia\Inertia;

class ServiceController
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (user()->cannot('viewAny', Service::class)) {
            abort(403);
        }

        return Inertia::render('pages/services/index', [
            'services' => fn() => getResource(Service::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (user()->cannot('create', Service::class)) {
            abort(403);
        }

        return Inertia::render('pages/services/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request) {
        if (user()->cannot('create', Service::class)) {
            abort(403);
        }
        Service::create($request->only('name', 'duration'));

        return redirect()->route('services.index')->with('success', 'Запись успешно создана');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service) {
        if (user()->cannot('update',  $service)) {
            abort(403);
        }
        return Inertia::render('pages/services/edit', [
            'services' => fn() => getResource($service),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service) {
        if (user()->cannot('update',  $service)) {
            abort(403);
        }

        $service->update($request->only('name', 'duration'));

        return redirect()->route('services.index')->with('success', 'Запись успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service) {
        if (user()->cannot('delete',  $service)) {
            abort(403);
        }
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Запись удалена');
    }
}
