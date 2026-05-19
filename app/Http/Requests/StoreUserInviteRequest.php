<?php

namespace App\Http\Requests;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserInviteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email", "unique:" . User::class . ",email"],
            "division_id" => ["nullable", "exists:" . Division::class . ",id"],
        ];
    }
}
