<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\CarbonImmutable;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\Division\ServiceFactory> */
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected
        $table = 'main__services',
        $fillable = [
            'name',
            'duration',
        ];

    protected function casts(): array
    {
        return [
            'duration' => 'datetime:H:i',
        ];
    }

    public function getShedulesFromWorker(User $worker, CarbonImmutable $date){
        $shedule = $worker->shedules()->where('day_of_the_week_id', $date->dayOfWeek())->first();

        $step = $this->duration->format('H') . ' hours ' .  $this->duration->format('i') . ' minutes ' . $this->duration->format('s') . ' seconds';
        $availableTimes = collect($shedule->date_start->toPeriod($shedule->date_end, $step)->toArray())->map(fn($date) => $date->format('H:i:s'));

        $subscribes = $worker
            ->subscribes()
            ->whereBetween('start_at', [$date->startOfDay(), $date->endOfDay()])
            ->where('service_id', $this->id)
            ->get();

        $busyTimes = $subscribes->map(fn($subscribe) => $subscribe->start_at->format('H:i:s'));

        return $availableTimes->diff($busyTimes)->values();
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'main__user_service', 'service_id', 'user_id');
    }
}
