<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeekendRequest;
use App\Http\Requests\UpdateWeekendRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Division;
use App\Models\UserWeekends;
use App\Http\Resources\WorkerResource;
use Carbon\CarbonImmutable;

class WeekendController
{
    public function index(Division $division, User $worker)
    {
        $user_weekends = $worker
            ->weekends()
            ->where('division_id', $division->id)
            ->orderByDesc('date_start')
            ->get();

        return Inertia::render('pages/weekends/index', [
            'worker' => WorkerResource::make($worker),
            'weekends' => $user_weekends->toResourceCollection(),
        ]);
    }

    public function create(Division $division, User $worker)
    {
        return Inertia::render('pages/weekends/create', [
            'worker' => WorkerResource::make($worker),
        ]);
    }

    public function store(StoreWeekendRequest $request, Division $division, User $worker)
    {
        $worker->weekends()->create(array_merge($request->validated(), [
            'user_id' => $worker->id,
            'division_id' => $division->id
        ]));

        return redirect()->route('weekends.index', [
            'worker' => $worker->id,
            'division' => $division->id
        ])->with('success', 'Запись успешно добавлена');
    }

    public function edit(Division $division, User $worker, UserWeekends $weekend)
    {
        return Inertia::render('pages/weekends/edit', [
            'worker' => WorkerResource::make($worker),
            'weekend' => $weekend
        ]);
    }

    public function update(UpdateWeekendRequest $request, Division $division, User $worker, UserWeekends $weekend)
    {
        $weekend->update(array_merge($request->validated(), [
            'user_id' => $worker->id,
            'division_id' => $division->id
        ]));

        return redirect()->route('weekends.index', [
            'worker' => $worker->id,
            'division' => $division->id
        ])->with('success', 'Запись успешно изменена');
    }

    public function destroy(Division $division, User $worker, UserWeekends $weekend)
    {
        $weekend->forceDelete();

        return back()->with('success', 'Запись удалена');
    }
}
