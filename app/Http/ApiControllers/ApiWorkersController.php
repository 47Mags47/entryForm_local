<?php

namespace App\Http\ApiControllers;

use App\Http\Resources\WorkerResource;
use App\Models\User;
use App\Models\Service;
use App\Models\WorkSchedule;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiWorkersController
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

    public function getAvailableWeekdays(Request $request): array
    {
        $worker = User::find(($request->input('worker_id')));
        $schedule = WorkSchedule::where('user_id', $worker->id)
            ->pluck('day_of_the_week_id')
            ->unique()
            ->values()
            ->toArray();

        $today = Carbon::now();
        $endDate = Carbon::now()->addMonth()->endOfDay();
        $availableDates = [];

        // Доступные дни на месяц вперёд, учитывая отпуска работника
        $weekends = $worker->weekends()->get();

        while ($today <= $endDate) {
            // Есть ли вообще отпуск в интервале месяца
            $weekend = $weekends->first(function ($weekend) use ($today) {
                return $today->between(
                    $weekend->date_start->startOfDay(),
                    $weekend->date_end->endOfDay()
                );
            });

            if ($weekend) {
                if (!$weekend->allow_meeting) {
                    $today->addDay();
                    continue;
                }
            }

            if (in_array($today->dayOfWeekIso, $schedule))
                $availableDates[] = $today->copy();

            $today->addDay();
        }

        return $availableDates;
    }

    public function workersFromService(Request $request): ResourceCollection
    {
        $service = Service::findOrFail($request->input('service_id'));
        $division = Division::findOrFail($request->input('division_id'));

        $workers = $service->workers()
            ->whereHas('divisions', function ($query) use ($division) {
                $query->whereKey($division->id);
            })
            ->get();

        return WorkerResource::collection($workers);
    }

    public static function workersFromDates(Request $request)
    {
        $division = Division::findOrFail($request->input('division_id'));
        $from = Carbon::parse($request->input('date_start'));
        $to = Carbon::parse($request->input('date_end'));

        $free_users = User::query()
            ->whereKeyNot($request->input('worker_id'))
            ->whereHas('divisions', function ($query) use ($division) {
                $query->whereKey($division->id);
            })
            ->whereDoesntHave('weekends', function ($query) use ($division, $from, $to) {
                $query
                    ->where('division_id', $division->id)
                    ->where('date_start', '<=', $to)
                    ->where('date_end', '>=', $from);
            })
            ->get();

        return $free_users;
    }
}
