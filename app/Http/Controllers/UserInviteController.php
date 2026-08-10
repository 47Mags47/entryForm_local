<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInviteRequest;
use App\Jobs\SendInviteJob;
use App\Models\Division;
use App\Models\UserInvite;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserInviteController
{
    public function create(Request $request) {
        if (!(user()->hasRole('admin')
            or (user()->hasRole('division_admin')))) {
            abort(403);
        }

        $division = Division::whereKey($request->division)->first();

        return Inertia::render("pages/invites/create", [
            "current_division" => fn() => getResource($division),
        ]);
    }

    public function store(StoreUserInviteRequest $request)
    {
        if (!(user()->hasRole('admin') or (user()->hasRole('division_admin')))) {
            abort(403);
        }

        $userInvite = UserInvite::create([
            'email' => $request->input('email'),
            'division_id' => $request->input('division_id'),
            'token' => Str::random(40),
        ]);

        SendInviteJob::dispatch($userInvite);

        return redirect()->route("workers.index", ['division' => $request->input('division_id')])
            ->with("success", "Приглашение успешно отправлено");
    }

    public function accept(string $token)
    {
        $invite = UserInvite::where('token', $token)->first();

        if ($invite === null)
            return abort(404);

        return redirect()->route("workers.create", ["token" => $invite->token]);
    }

    public function acceptForUserCreated(string $token, Request $request)
    {
        $invite = UserInvite::where('token', $token)->first();

        if ($invite === null)
            return abort(404);

        $user = User::where('email', $invite->email)->first();

        $division = $invite->division;

        $user->divisions()->attach($division->id, [
            'role_id' => UserRole::byCode('division_worker')->id,
        ]);

        return redirect()->route("events.index", ["division" => $request->input('division')]);
    }
}
