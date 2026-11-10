<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeNoteRequest;
use App\Models\Division;
use App\Models\Subscribe;
use App\Models\SubscribeNote;
use Inertia\Inertia;

class SubscribeNoteController
{
    public function create(Division $division, Subscribe $subscribe)
    {
        return Inertia::render('pages/subscribes/subscribeNotes/create', [
            'division' => getResource($division),
            'subscribe' => getResource($subscribe)
        ]);
    }
    public function store(StoreSubscribeNoteRequest $request, Division $division, Subscribe $subscribe)
    {
        $data = $request->validated();

        SubscribeNote::create([
                'worker_id' => user()->id,
                'subscribe_id' => $subscribe->id,
                ...$data
            ]
        );

        return redirect()->route('subscribes.show', [
            'division' => $division->id,
            'subscribe' => $subscribe->id
        ])->with('success', 'Заметка добавлена!');
    }
}
