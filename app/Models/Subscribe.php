<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscribe extends Model
{
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected $table = 'main__subscribes';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'comment',
        'division_id',
        'service_id',
        'worker_id',
        'start_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime:Y-m-d H:i',
        ];
    }

    public function scopeWhereHasAccess(Builder $query)
    {
        return $query->where(function ($query) {
            if (user()->hasRole('admin'))
                return $query;

            if (user()->hasRole('division_admin'))
                return $query->orWhere('division_id', user()->division->id);

            if (user()->hasRole('division_worker'))
                return $query->where('id', null)->orWhere(function ($query) {

                    $query->orWhere('worker_id', user()->id);

                    $services_ids = user()->services->pluck('id');
                    $division_user_ids = user()->division->users->pluck('id');
                    $other_user_ids = UserService::whereIn('service_id', $services_ids)->whereIn('user_id', $division_user_ids)->get('user_id')->pluck('user_id');

                    $query->orWhere(function ($query) use ($other_user_ids, $services_ids) {
                        $query->whereIn('worker_id', $other_user_ids)->whereIn('service_id', $services_ids);
                    });
                });

            return $query->whereKey(null);
        });
    }


    ### методы
    ##################################################

    ### Связи
    ##################################################
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id')->withTrashed();
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id')->withTrashed();
    }
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id', 'id')->withTrashed();
    }
}
