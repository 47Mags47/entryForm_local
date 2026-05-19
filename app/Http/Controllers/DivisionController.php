<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\City;
use App\Models\DayOfTheWeek;
use App\Models\Division;
use App\Models\DivisionGroup;
use Inertia\Inertia;

class DivisionController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (user()->cannot('viewAny', Division::class)) {
            abort(403);
        }

        return Inertia::render('pages/divisions/index', [
            'divisions' => fn() => getResource(Division::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (user()->cannot('create', Division::class)) {
            abort(403);
        }

        return Inertia::render('pages/divisions/create', [
            'cities' => fn() => City::get(['id', 'name']),
            'division_group' => fn() => DivisionGroup::get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request)
    {
        if (user()->cannot('create', Division::class)) {
            abort(403);
        }

        $division = Division::create($request->only('name', 'address', 'city_id', 'group_id', 'url'));

        foreach ($request->get('shedules') as $day_code => $shedule) {
            $division->shedules()->create([
                'day_of_the_week_id' => DayOfTheWeek::byCode($day_code)->id,
                'date_start' => $shedule['date_start'],
                'date_end' => $shedule['date_end'],
                'lunch_start' => $shedule['lunch_start'],
                'lunch_end' => $shedule['lunch_end'],
            ]);
        }
        return redirect()->route('divisions.index')->with('success', 'Запись успешно добавлена');
    }

    public function show(Division $division)
    {
        if (user()->cannot('view', $division)) {
            abort(403);
        }

        return Inertia::render(
            'pages/divisions/show',
            [
                'division' => fn() => getResource($division),
                'cities' => fn() => City::get(['id', 'name']),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        if (user()->cannot('update', $division)) {
            abort(403);
        }

        return Inertia::render('pages/divisions/edit', [
            'division' => fn() => getResource($division),
            'division_group' => fn() => DivisionGroup::get(['id', 'name']),
            'cities' => fn() => City::get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request, Division $division)
    {
        if (user()->cannot('update', $division)) {
            abort(403);
        }

        $division->update($request->only('name', 'address', 'city_id', 'group_id', 'url'));

        $division->shedules()->delete();
        foreach ($request->get('shedules') as $day_code => $shedule) {
            $division->shedules()->create([
                'day_of_the_week_id' => DayOfTheWeek::byCode($day_code)->id,
                'date_start' => $shedule['date_start'],
                'date_end' => $shedule['date_end'],
                'lunch_start' => $shedule['lunch_start'],
                'lunch_end' => $shedule['lunch_end'],
            ]);
        }

        return user()->hasRole('admin')
            ? redirect()->route('divisions.index')->with('success', 'Запись успешно изменена')
            : redirect()->route('divisions.show', compact('division'))->with('success', 'Запись успешно изменена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        if (user()->cannot('delete', $division)) {
            abort(403);
        }

        $division->delete();

        return back()->with('success', 'Запись удалена');
    }
}
