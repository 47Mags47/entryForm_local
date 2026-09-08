<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use App\Http\Resources\CityResource;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CityController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        if (user()->cannot('viewAny', City::class)) {
            abort(403);
        }

        $cities = City::query()
            ->when(
                $request->filled('filter.city'),
                fn($query) => $query->where(
                    'name',
                    'like',
                    '%' . $request->input('filter.city') . '%'
                )
            );

        return Inertia::render('pages/cities/index', [
            'cities' => fn() => getResource($cities),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (user()->cannot('create', City::class)) {
            abort(403);
        }
        return Inertia::render('pages/cities/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request) {
        if (user()->cannot('create', City::class)) {
            abort(403);
        }

        City::create($request->only('name'));

        return redirect()->route('cities.index')->with('success', 'Запись успешно создана');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city) {
        if (user()->cannot('update', $city)) {
            abort(403);
        }

        return Inertia::render('pages/cities/edit', [
            'city' => new CityResource($city),
]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city){
        if (user()->cannot('update', $city)) {
            abort(403);
        }
        $city->update($request->only('name'));

        return redirect()->route('cities.index')->with('success', 'Запись успешно изменена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city){
        if (user()->cannot('delete', $city)) {
            abort(403);
        }
        $city->delete();

        return redirect()->route('cities.index')->with('success', 'Запись удалена');
    }
}
