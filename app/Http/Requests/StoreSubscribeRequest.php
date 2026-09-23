<?php

namespace App\Http\Requests;

use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscribeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'middle_name' => ['nullable', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'regex:/^((8|\+7|7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            'email' => ['nullable', 'email', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'worker_id' => ['required', 'exists:' . User::class . ',id'],
            'service_id' => ['required', 'exists:' . Service::class . ',id'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'comment' => ['nullable', 'string', 'max:255']
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $worker = User::find($this->input('worker_id'));
            $date = Carbon::parse($this->input('start_date'));

            $weekend = $worker->weekends->first(function ($weekend) use ($date) {
                return $date->between(
                    $weekend->date_start->copy()->startOfDay(),
                    $weekend->date_end->copy()->endOfDay()
                );
            });

            if ($weekend && !$weekend?->allow_meeting) {
                $validator->errors()->add(
                    'start_date',
                    'Запись на эту дату не разрешена (сотрудник в отпуске)'
                );
            }
        });
    }
}
