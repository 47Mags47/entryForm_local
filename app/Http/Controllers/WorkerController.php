<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\DayOfTheWeek;
use App\Models\Division;
use App\Models\Service;
use App\Models\User;
use App\Models\UserInvite;
use App\Models\UserRole;
use App\Models\UserService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WorkerController
{
    /**
     * Display a listing of the resource.s
     */
    public function index(Request $request, Division $division)
    {
        if (!(user()->hasRole('admin')
            or (user()->hasRole('division_admin') and user()->divisions()->where('id', $division->id)->exists())
        )) {
            abort(403);
        }

        if ($request->boolean('trashed'))
            return Inertia::render("pages/workers/index", [
                'users' => fn() =>  WorkerResource::collection($division->users()->withTrashed()->get()),
                'trashed' => $request->boolean('trashed') === null ? false : $request->boolean('trashed')
            ]);
        else
            return Inertia::render("pages/workers/index", [
                'users' => fn() =>  WorkerResource::collection($division->users()->get()),
                'trashed' => $request->boolean('trashed') === null ? false : $request->boolean('trashed')
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $token)
    {
        $invite = UserInvite::where('token', $token)->first();

        if ($invite === null)
            return abort(404);


        return Inertia::render("pages/workers/create", [
            "invite" => fn() => getResource($invite),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerRequest $request)
    {
        $invite = UserInvite::where('token', $request->input('token'))->first();

        if ($invite === null)
            return abort(404);

        $user = User::create([
            'last_name' => $request->string('last_name')->trim(),
            'first_name' => $request->string('first_name')->trim(),
            'middle_name' => $request->string('middle_name')->trim(),
            'email' => $request->email,
            'phone' => $request->phone,
            'office' => $request->string('office')->trim(),
            'password' => Hash::make($request->password),
        ]);

        $user->divisions()->attach($invite->division->id, [
            'role_id' => UserRole::byCode('division_worker')->id,
        ]);

        Auth::login($user);

        return redirect()->route('events.index', ['division' => $invite->division->id])->with('success', 'Вы успешно зарегистрировались!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division, User $worker, Request $request)
    {
        if (user()->hasRole('admin') || user()->hasRole('division_admin', $division))
            return Inertia::render("pages/workers/edit", [
                "worker"   => fn() => WorkerResource::make($worker),
                'services' => fn() => Service::get(['id', 'name']),
            ]);

        else
            return abort(403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Division $division, User $worker, UpdateWorkerRequest $request)
    {
        if (user()->hasRole('admin') || user()->hasRole('division_admin', $division)) {
            UserService::where('user_id', $worker->id)->delete();
            foreach ($request->input('service_ids') as $service_id) {
                UserService::create([
                    'user_id' => $worker->id,
                    'service_id' => $service_id,
                ]);
            }

            $worker->shedules()->delete();
            foreach ($request->input('shedules') as $day => $dates) {
                $worker->shedules()->create([
                    'day_of_the_week_id' => DayOfTheWeek::byCode($day)->id,
                    'date_start' => $dates['date_start'],
                    'date_end' => $dates['date_end'],
                    'lunch_start' => $dates['lunch_start'],
                    'lunch_end' => $dates['lunch_end'],
                ]);
            }

            return redirect()
                ->route('workers.index', ['division' => $division->id])
                ->with('success', 'Рассписание обновлено');
        } else
            return abort(403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division, User $worker)
    {
        if (user()->hasRole('admin') || user()->hasRole('division_admin', $division)) {
            $worker->delete();

            return redirect()->route('workers.index', ['division' => $division->id])->with('success', value: 'Пользователь удален');
        } else
            abort(403);
    }

    public function restore(Division $division, User $worker)
    {
        if (user()->hasRole('admin') || user()->hasRole('division_admin', $division)) {
            $worker->restore();

            return redirect()->route('workers.index', ['division' => $division->id])->with('success', value: 'Пользователь восстановлен');
        } else
            return abort(403);
    }
}
