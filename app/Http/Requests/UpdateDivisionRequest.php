<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Division;
use App\Models\DayOfTheWeek;
use App\Models\DivisionGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UpdateDivisionRequest extends FormRequest
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
            'address' => ['required', 'string', 'min:3'],
            'city_id' => ['required', 'exists:' . City::class . ',id'],
            'group_id' => ['nullable', 'exists:' . DivisionGroup::class . ',id'],
            'url' => ['nullable', 'url'],
            'shedules' => ['required', 'array'],
            'shedules.*' => ['nullable', 'array'],
            'shedules.*.date_start' => ['required', 'date_format:H:i'],
            'shedules.*.date_end' => ['required', 'date_format:H:i'],
            'shedules.*.lunch_start' => ['required', 'date_format:H:i'],
            'shedules.*.lunch_end' => ['required', 'date_format:H:i'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $shedules = $this->input('shedules', []);

            $days = DayOfTheWeek::all()->keyBy('code');

            foreach ($shedules as $dayCode => $shedule) {
                if (!isset($days[$dayCode])) {
                    continue;
                }

                $day = $days[$dayCode];

                if (!empty($shedule['date_start']) && !empty($shedule['date_end'])) {
                    try {
                        $start = Carbon::createFromFormat('H:i', $shedule['date_start']);
                        $end = Carbon::createFromFormat('H:i', $shedule['date_end']);
                    } catch (\Exception $e) {
                        continue;
                    }

                    if ($start->gte($end)) {
                        $validator->errors()->add(
                            "shedules",
                            "Время начала должно быть меньше времени окончания ({$day->name})."
                        );
                    }
                }
            }
        });
    }
}
