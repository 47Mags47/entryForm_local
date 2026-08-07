<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionAdminRequest;
use App\Models\Division;
use App\Models\User;
use App\Models\UserRole;
use Inertia\Inertia;

class DivisionAdminController
{
    public function store(StoreDivisionAdminRequest $request, Division $division)
    {
        if (
            $request->input('role_code') !== 'admin'
            and (
                user()->hasRole('admin')
                or (user()->hasRole('division_admin') and user()->divisions()->where('id', $division->id)->exists())
            )
        ) {
            $roleId = UserRole::byCode($request->input('role_code'))->id;
            $user = User::findOrFail($request->user_id);
            $user->divisions()->updateExistingPivot($division->id, [
                'role_id' => $roleId,
            ]);

            return back()->with('success', 'Роль ' . UserRole::byCode($request->input('role_code'))->name . ' успешно назначена');
        } else
            return abort(403);
    }
}
