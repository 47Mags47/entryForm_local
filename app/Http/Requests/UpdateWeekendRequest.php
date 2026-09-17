<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeekendRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'replacement_id' => [
                'required',
                'exists:' . User::class . ',id'
            ],
            'date_start' => [
                'required',
                'date_format:Y-m-d',
            ],

            'date_end' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:date_start',
            ],
        ];
    }
}
