<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Division;
use \App\Models\DayOfTheWeek;
use Illuminate\Support\Carbon;

class UpdateWorkerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shedules' => ['array', 'min:0', 'max:7'],
            'shedules.*' => ['nullable', 'array'],
            'shedules.*.date_start' => ['required', 'date_format:H:i'],
            'shedules.*.date_end' => ['required', 'date_format:H:i'],
            'shedules.*.lunch_start' => ['nullable', 'date_format:H:i'],
            'shedules.*.lunch_end' => ['nullable', 'date_format:H:i'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $shedules = $this->input('shedules', []);

            $divisionId = $this->route('division') ?? $this->input('division_id');
            $division = Division::with('shedules')->find($divisionId);

            if (!$division) {
                return;
            }

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
                    $divisionShedule = $division->shedules
                        ->where('day_of_the_week_id', $day->number)
                        ->first();

                    if ($divisionShedule) {
                        $divisionStart = Carbon::parse($divisionShedule->date_start)->setSeconds(0);
                        $divisionEnd = Carbon::parse($divisionShedule->date_end)->setSeconds(0);

                        if ($start->lt($divisionStart) || $end->gt($divisionEnd)) {
                            $validator->errors()->add(
                                "shedules",
                                "Расписание работника ({$day->name}) выходит за пределы расписания подразделения ({$divisionStart->format('H:i')}-{$divisionEnd->format('H:i')})."
                            );
                        }
                    }
                }
            }
        });
    }
}
