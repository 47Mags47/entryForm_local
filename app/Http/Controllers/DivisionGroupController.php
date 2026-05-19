<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreDivisionGroupRequest;
use App\Http\Requests\UpdateDivisionGroupRequest;
use App\Http\Resources\DivisionGroupResource;
use App\Models\DivisionGroup;
use Inertia\Inertia;

class DivisionGroupController
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
         if (!(user()->hasRole('admin'))) {
            abort(403);
        }
        return Inertia::render('pages/division-group/index', [
            'division_group' => fn() => getResource(DivisionGroup::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (!(user()->hasRole('admin'))) {
            abort(403);
        }
        return Inertia::render('pages/division-group/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionGroupRequest $request) {
        if (!(user()->hasRole('admin'))) {
            abort(403);
        }

        DivisionGroup::create($request->only('name'));

        return redirect()->route('division-group.index')->with('success', 'Запись успешно создана');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivisionGroup $division_group) {
        if (!(user()->hasRole('admin'))) {
            abort(403);
        }

        return Inertia::render('pages/division-group/edit', [
            'division_group' => new DivisionGroupResource($division_group),
]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionGroupRequest $request, DivisionGroup $divisionGroup){
        if (!(user()->hasRole('admin'))) {
            abort(403);
        }
        $divisionGroup->update($request->only('name'));

        return redirect()->route('division-group.index')->with('success', 'Запись успешно изменена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DivisionGroup $divisionGroup){
        if (!(user()->hasRole('admin'))) {
            abort(403);
        }
        $divisionGroup->delete();

        return redirect()->route('division-group.index')->with('success', 'Запись удалена');
    }
}
