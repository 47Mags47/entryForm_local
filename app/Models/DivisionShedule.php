<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class DivisionShedule extends Model
{
    protected
    $table = "main__division_shedule",
    $fillable = [
        'date_start',
        'date_end',
        'lunch_start',
        'lunch_end',
        'division_id',
        'day_of_the_week_id',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'date_start' => 'datetime:H:i',
            'date_end' => 'datetime:H:i',
            'lunch_start' => 'datetime:H:i',
            'lunch_end' => 'datetime:H:i',
        ];
    }

    ### Связи
    ##################################################
    public function dayOfTheWeek(): BelongsTo
    {
        return $this->belongsTo(DayOfTheWeek::class, 'day_of_the_week_id');
    }
}
