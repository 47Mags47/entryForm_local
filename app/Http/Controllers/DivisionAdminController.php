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
                or (user()->hasRole('division_admin') and user()->division->id === $division->id)
            )
        ) {
            $division->users()->whereKey($request->user_id)->update([
                'role_id' => UserRole::byCode($request->input('role_code'))->id,
            ]);

            return back()->with('success', 'Роль ' . $division->users()->find($request->user_id)->role->name . ' успешно назначена');
        } else
            return abort(403);
    }

    public function destroy(Division $division, User $division_admin)
    {
        if (
            !(user()->hasRole('admin')
                or (user()->hasRole('division_admin')
                    and user()->division->id === $division->id))
        ) {
            abort(403);
        }

        if ($division_admin->id === user()->id) {
            return back()->with('warning', 'Вы не можете удалить самого себя');
        }

        $division_admin->update(['role_id' => UserRole::byCode('division_worker')->id]);

        return back()->with('success', 'Администратор подразделения удален');
    }
}
