<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeNoteRequest;
use App\Models\Division;
use App\Models\Subscribe;
use App\Models\SubscribeNote;

class SubscribeNoteController
{
    public function store(StoreSubscribeNoteRequest $request, Division $division, Subscribe $subscribe)
    {
        $data = $request->validated();

        SubscribeNote::updateOrCreate(
            [
                'worker_id' => user()->id,
                'subscribe_id' => $subscribe->id,
            ],
            $data
        );

        return redirect()->route('subscribes.index', ['division' => $division->id])->with('success', 'Заметка добавлена!');
    }
}
