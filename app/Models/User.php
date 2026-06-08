<?php

namespace App\Models;

use App\Http\Resources\SubscribeTimeLineResource;
use App\Models\UserRole;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use App\Models\ChangeEmailToken;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    ### Настройки
    ##################################################
    protected
    $table = 'main__users',
    $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'office',
        'password',
        'role_id',
        'division_id'
    ],
    $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    ### Методы
    ##################################################
    public function hasRole(string $role)
    {
        return user()->role_id === UserRole::where('code', $role)->get()->first()->id;
    }

    public function getTimeLine(Carbon|CarbonImmutable $day)
    {

        $shedule = $this
            ->division
            ->shedules()
            ->where('day_of_the_week_id', DayOfTheWeek::byNumber($day->dayOfWeekIso)->id)
            ->first();

        $date_start = $day->setTime($shedule?->date_start->hour ?? 0, $shedule?->date_start->minute ?? 0);
        $date_end = $day->setTime($shedule?->date_end->hour ?? 0, $shedule?->date_end->minute ?? 0);

        $userShedules = $this
            ->subscribes()
            ->where(function ($query) use ($shedule, $date_start, $date_end) {
                if ($shedule !== null)
                    $query->whereBetween('start_at', [$date_start, $date_end]);
                else
                    $query->whereKey(null);
            })
            ->get()
            ->map(fn($subscribe) => SubscribeTimeLineResource::make($subscribe))
            ->groupBy(
                fn($subscribe) => $subscribe->start_at->minute > 30
                ? $subscribe->start_at->startOfHour()->addMinutes(30)->format('Y-m-d H:i:s')
                : $subscribe->start_at->startOfHour()->format('Y-m-d H:i:s')
            );

        if ($shedule === null)
            return $userShedules;

        $interval = $date_start->toPeriod($date_end, '30 minutes');
        $intervalCollection = collect($interval->map(fn($time) => [$time->format('Y-m-d H:i:s') => collect([])]))->collapse();

        return $intervalCollection->merge($userShedules);
    }

    ### Связи
    ##################################################
    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(Service::class, UserService::class, 'user_id', 'id', 'id', 'service_id')->withTrashed();
    }

    public function shedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class, 'user_id', 'id');
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(Subscribe::class, 'worker_id', 'id');
    }

    public function changeEmailTokens(){
        return $this->hasMany(ChangeEmailToken::class, 'user_id');
    }
}
