<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionUnavailablePeriodRequest;
use App\Http\Requests\UpdateDivisionUnavailablePeriodRequest;
use App\Models\Division;
use App\Models\DivisionUnavailablePeriod;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class DivisionUnavailablePeriodController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(DivisionUnavailablePeriod::class, 'unavailable_period');
    }

    public function index(Division $division)
    {
        $unavailable_periods = $division->unavailable_periods()
            ->orderByDesc('date_start')
            ->paginate(25);

        return Inertia::render('pages/unavailable-periods/index', [
            'unavailable_periods' => $unavailable_periods->toResourceCollection(),
        ]);
    }

    public function create(Division $division)
    {
        return Inertia::render('pages/unavailable-periods/create');
    }

    public function store(Division $division, StoreDivisionUnavailablePeriodRequest $request)
    {
        $data = $request->validated();
        $division->unavailable_periods()->create($data);

        return redirect()->route('unavailable-periods.index', [
            'division' => $division,
        ])->with('success', 'Запись успешно добавлена');
    }

    public function edit(Division $division, DivisionUnavailablePeriod $unavailable_period)
    {
        $unavailable_period = $division->unavailable_periods()
            ->findOrFail($unavailable_period->id);

        return Inertia::render('pages/unavailable-periods/edit', [
            'unavailable_period' => $unavailable_period->toResource(),
        ]);
    }

    public function update(Division $division, DivisionUnavailablePeriod $unavailable_period, UpdateDivisionUnavailablePeriodRequest $request)
    {
        $data = $request->validated();
        $division->unavailable_periods()
            ->findOrFail($unavailable_period->id)
            ->update($data);

        return redirect()->route('unavailable-periods.index', [
            'division' => $division,
        ])->with('success', 'Запись успешно обновлена');
    }

    public function destroy(Division $division, DivisionUnavailablePeriod $unavailable_period)
    {
        $division->unavailable_periods()
            ->findOrFail($unavailable_period->id)
            ->delete();

        return back()->with('success', 'Запись удалена');
    }
}
