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
    public function index(Division $division, User $user)
    {
        abort_unless(
            $user->hasDivision($division),
            404
        );

        $user_weekends = $user
            ->weekends()
            ->where('division_id', $division->id)
            ->orderByDesc('date_start')
            ->get();

        return Inertia::render('pages/weekends/index', [
            'worker' => WorkerResource::make($user),
            'weekends' => $user_weekends->toResourceCollection(),
        ]);
    }

    public function create(Division $division, User $user)
    {
        abort_unless(
            $user->hasDivision($division),
            404
        );

        return Inertia::render('pages/weekends/create', [
            'worker' => WorkerResource::make($user),
        ]);
    }

    public function store(StoreWeekendRequest $request, Division $division, User $user)
    {
        abort_unless(
            $user->hasDivision($division),
            404
        );

        $user->weekends()->create(array_merge($request->validated(), [
            'user_id' => $user->id,
            'division_id' => $division->id
        ]));

        return redirect()->route('weekends.index', [
            'user' => $user->id,
            'division' => $division->id
        ])->with('success', 'Запись успешно добавлена');
    }

    public function edit(Division $division, User $user, UserWeekends $weekend)
    {
        abort_unless(
            $weekend->user_id === $user->id &&
            $weekend->division_id === $division->id,
            404
        );

        return Inertia::render('pages/weekends/edit', [
            'worker' => WorkerResource::make($user),
            'weekend' => $weekend
        ]);
    }

    public function update(UpdateWeekendRequest $request, Division $division, User $user, UserWeekends $weekend)
    {
        abort_unless(
            $weekend->user_id === $user->id &&
            $weekend->division_id === $division->id,
            404
        );

        $weekend->update(array_merge($request->validated(), [
            'user_id' => $user->id,
            'division_id' => $division->id
        ]));

        return redirect()->route('weekends.index', [
            'user' => $user->id,
            'division' => $division->id
        ])->with('success', 'Запись успешно изменена');
    }

    public function destroy(Division $division, User $user, UserWeekends $weekend)
    {
        abort_unless(
            $weekend->user_id === $user->id &&
            $weekend->division_id === $division->id,
            404
        );

        $weekend->forceDelete();

        return back()->with('success', 'Запись удалена');
    }
}
