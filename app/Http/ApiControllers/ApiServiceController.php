<?php

namespace App\Http\ApiControllers;

use App\Models\User;
use App\Models\Service;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class ApiServiceController
{
    public function shedulesFromWorker(Request $request)
    {
        $worker     = User::findOrFail($request->input('worker_id'));
        $service    = Service::findOrFail($request->input('service_id'));
        $date       = CarbonImmutable::parse($request->input('date'));

        $times = $service->getAvailableTimeFromUser($worker, $date);

        return response()->json(
            collect($times)->map(function ($time) {
                return [
                    'label' => $time,
                    'value' => $time,
                ];
            })->values()
        );
    }

    public function getAvailableWeekdays(Request $request): array
    {
        return WorkSchedule::where('user_id', $request->input('worker_id'))
            ->pluck('day_of_the_week_id')
            ->unique()
            ->values()
            ->toArray();
    }
}
