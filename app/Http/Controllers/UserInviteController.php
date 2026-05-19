<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInviteRequest;
use App\Http\Requests\UpdateUserInviteRequest;
use App\Jobs\SendInviteJob;
use App\Models\Division;
use App\Models\UserInvite;
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

        $division = Division::whereKey($request->division_id)->first();

        return Inertia::render("pages/invites/create", [
            "division" => fn() => getResource($division),
        ]);
    }

    public function store(StoreUserInviteRequest $request){
        if (!(user()->hasRole('admin')
            or (user()->hasRole('division_admin')))) {
            abort(403);
        }

        $userInvite = UserInvite::create([
            'email' => $request->input('email'),
            'division_id' => $request->input('division_id'),
            'token' => Str::random(40),
        ]);

        SendInviteJob::dispatch($userInvite);

        return redirect()->route("workers.index", ['division' => $request->input('division_id')])
            ->with("message", "Приглашение успешно отправлено");
    }

    public function accept(string $token)
    {
        $invite = UserInvite::where('token', $token)->first();

        if ($invite === null)
            return abort(404);

        return redirect()->route("workers.create", ["token" => $invite->token]);
    }
}
