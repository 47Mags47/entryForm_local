<?php

namespace App\Http\ApiControllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
// use Inertia\Inertia;

class ApiServiceController
{
    public function shedulesFromWorker(Request $request)
    {
        $worker = User::findOrFail($request->input('worker_id'));
        $service = Service::findOrFail($request->input('service_id'));
        $date = CarbonImmutable::parse($request->input('date'));

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
}
