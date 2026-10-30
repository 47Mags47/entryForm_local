<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDivisionUnavailablePeriodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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

            $division = $this->route('division');
            $unavailable_period = $this->route('unavailable_period');

            $data_exists = $division->unavailable_periods()
                ->where('id', '!=', $unavailable_period->id)
                ->where('division_id', $division->id)
                ->where('date_start', '<=', $this->date('date_end'))
                ->where('date_end', '>=', $this->date('date_start'))
                ->exists();

            if ($data_exists) {
                $validator->errors()->add(
                    'date_start',
                    'Указанный период уже существует или пересекается с другим периодом организации'
                );
            }
        });
    }
}
