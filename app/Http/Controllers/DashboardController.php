<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDashboardRequest;
use App\Models\Division;
use App\Models\User;
use Inertia\Inertia;

class DashboardController
{
    public function show(User $user)
    {
        if (!(user()->id === $user->id)) {
            abort(403);
        }
        return Inertia::render('pages/dashboard/show', [
            'division' => fn() => getResource(Division::class),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!(user()->id === $user->id)) {
            abort(403);
        }

        return Inertia::render('pages/dashboard/edit', [
            'division' => fn() => getResource(Division::class),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDashboardRequest $request, User $user)
    {
        if (!(user()->id === $user->id)) {
            abort(403);
        }

        $user->update($request->only('first_name', 'middle_name', 'last_name', 'phone', 'office', 'receiveMail'));

        return redirect()->route('user.show', ['user' => $user->id])->with('success', 'Данные успешно изменены');
    }

}
