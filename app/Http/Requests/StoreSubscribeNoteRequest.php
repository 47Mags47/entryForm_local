<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscribeNoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'note' => ['required', 'string', 'min:0', 'max:255'],
        ];
    }
}
