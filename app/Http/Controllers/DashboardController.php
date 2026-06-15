<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDashboardRequest;
use App\Models\Division;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class DashboardController
{
    public function show(User $user)
    {
        if (!Gate::allows('dashboard', $user)) {
            abort(403);
        }

        return Inertia::render('pages/dashboard/show', [
            'user' => getResource($user),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!Gate::allows('dashboard', $user)) {
            abort(403);
        }

        return Inertia::render('pages/dashboard/edit', [
            'user' => getResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDashboardRequest $request, User $user)
    {
        if (!Gate::allows('dashboard', $user)) {
            abort(403);
        }

        $user->update($request->only('first_name', 'middle_name', 'last_name', 'phone', 'office', 'email'));

        return redirect()->route('user.show', ['user' => $user->id])->with('success', 'Данные успешно изменены');
    }

}
