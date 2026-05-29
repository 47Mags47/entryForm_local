<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDashboardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'min:3', 'max:255'],
            'middle_name'   => ['nullable', 'string', 'min:3', 'max:255'],
            'last_name'     => ['nullable', 'string', 'min:3', 'max:255'],
            'office'        => ['nullable', 'string', 'max:255' ]
        ];
    }
}
