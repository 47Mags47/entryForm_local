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
            'middle_name'   => ['required', 'string', 'min:3', 'max:255'],
            'last_name'     => ['required', 'string', 'min:3', 'max:255'],
            'office'        => ['required', 'string', 'max:255' ],
            'phone'         => ['required', 'string', 'regex:/\+7 \([0-9]{3}\) [0-9]{3} [0-9]{2}-[0-9]{2}/'],
            'receiveMail'   => ['nullable', 'boolean']
        ];
    }
}
