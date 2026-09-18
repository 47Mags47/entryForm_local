<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreWeekendRequest extends FormRequest
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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $worker = $this->route('worker');
            $division = $this->route('division');

            $exists = $worker->weekends()
                ->where('division_id', $division->id)
                ->where('date_start', '<=', $this->date_end)
                ->where('date_end', '>=', $this->date_start)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'date_start',
                    'У сотрудника уже есть отпуск в указанный период.'
                );
            }
        });
    }
}
