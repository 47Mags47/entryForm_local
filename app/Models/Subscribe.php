<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscribe extends Model
{
    /** @use HasFactory<\Database\Factories\User\SubscribeFactory> */
    use HasFactory;

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
            'start_at' => 'datetime',
        ];
    }

    public function scopeWhereHasAccess($query)
    {
        return $query->where(function ($query) {
            if(user()->hasRole('admin'))
                return $query;

            if (user()->hasRole('division_admin'))
                return $query->orWhere('division_id', user()->division->id);

            if (user()->hasRole('division_worker'))
                return $query->orWhere('worker_id', user()->id);

            return $query->whereKey(null);
        });
    }

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
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
}
