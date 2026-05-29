<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token'         => ['required', 'exists:' . UserInvite::class . ',token'],
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['nullable', 'string', 'max:255'],
            'middle_name'   => ['nullable', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:' . User::class . ',email'],
            'office'        => ['nullable', 'string', 'max:255' ],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
