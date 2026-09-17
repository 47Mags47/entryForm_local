<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeekendRequest;
use App\Http\Requests\UpdateWeekendRequest;
use App\Http\Resources\UserWeekendResource;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Division;
use App\Models\UserWeekends;
use App\Http\Resources\WorkerResource;

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
        $replacement = User::find($request->input('replacement_id'));
        abort_unless($replacement !== null, 404, 'Замещающий сотрудник не найден');

        $isReplacementExist = $replacement->divisions()->whereKey($division->id)->exists();
        abort_unless($isReplacementExist, 404, 'Замещающий сотрудник в подразделении не найден');

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
        $from     = $weekend->date_start;
        $to       = $weekend->date_end;

        $free_users = User::query()
            ->whereHas('divisions', function ($query) use ($division) {
                $query->whereKey($division->id);
            })
            ->whereDoesntHave('weekends', function ($query) use ($division, $from, $to) {
                $query
                    ->where('division_id', $division->id)
                    ->where('date_start', '<=', $to)
                    ->where('date_end', '>=', $from);
            })
            ->get();


        return Inertia::render('pages/weekends/edit', [
            'worker' => WorkerResource::make($worker),
            'weekend' => UserWeekendResource::make($weekend),
            'workers' => $free_users->toResourceCollection()
        ]);
    }

    public function update(UpdateWeekendRequest $request, Division $division, User $worker, UserWeekends $weekend)
    {
        $replacement = User::find($request->input('replacement_id'));
        abort_unless($replacement !== null, 404, 'Замещающий сотрудник не найден');

        $isReplacementExist = $replacement->divisions()->whereKey($division->id)->exists();
        abort_unless($isReplacementExist, 404, 'Замещающий сотрудник в подразделении не найден');

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
