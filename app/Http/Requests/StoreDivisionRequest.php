<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\DivisionGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreDivisionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'address'=> ['required', 'string','min:3'],
            'city_id' => ['required', 'exists:' . City::class . ',id'],
            'group_id' => ['nullable', 'exists:' . DivisionGroup::class . ',id'],
            'url' => ['nullable', 'url'],
            'shedules' => ['required', 'array'],
            'shedules.*' => ['nullable','array'],
            'shedules.*.date_start' => ['required','date_format:H:i'],
            'shedules.*.date_end' => ['required','date_format:H:i'],
            'shedules.*.lunch_start' => ['required','date_format:H:i'],
            'shedules.*.lunch_end' => ['required','date_format:H:i'],
        ];
    }
}
