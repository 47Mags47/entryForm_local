<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Frame;
use App\Models\City;
use Inertia\Inertia;
use Illuminate\Support\Str;

class FrameController
{
    public function index(Division $division)
    {
        return Inertia::render('pages/frames/index', [
            'cities'    => fn() => getResource(City::class),
            'division'  => fn() => getResource($division),
            'frame'     => fn() => getResource(Frame::where('division_id', $division->id)),
        ]);
    }
    public function store(Division $division)
    {
        $token = Str::random(40);
        Frame::create([
            'division_id' => $division->id,
            'token' => $token,
        ]);

        return back()->with('success', 'Фрейм создан');
    }

    public function update(Division $division, Frame $frame)
    {
        $frame->delete();

        $token = Str::random(40);
        Frame::create([
            'division_id' => $division->id,
            'token' => $token,
        ]);
        return back()->with('success', 'Фрейм обновлен');
    }
    public function destroy(Division $division, Frame $frame)
    {
        $frame->delete();

        return back()->with('success', 'Фрейм удален');

    }
}
