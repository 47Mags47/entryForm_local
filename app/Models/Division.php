<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Division extends Model
{
    /** @use HasFactory<\Database\Factories\Admin\DivisionFactory> */
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected
    $table = 'main__divisions';

    protected $fillable = [
        'name',
        'address',
        'city_id',
        'group_id',
        'url',
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            if ($model->users()->count() > 0)
                return abort(403, 'Невозможно удалить подразделение, пока в нем есть пользователи');
        });
    }

    ### Связи
    ##################################################
    /**
     * Get the user that owns the Division
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function shedules(): HasMany
    {
        return $this->hasMany(DivisionShedule::class, 'division_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'division_id');
    }

    public function admins(): HasMany
    {
        return $this->users()->where('role_id', UserRole::byCode('division_admin')->id);
    }

    public function workers(): HasMany
    {
        return $this->users()->where('role_id', UserRole::byCode('division_worker')->id);
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(Subscribe::class, 'division_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(DivisionGroup::class, 'group_id');
    }
}
