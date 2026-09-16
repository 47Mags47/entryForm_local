<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserWeekends extends Model
{
    ### Настройки
    ##################################################
    protected
    $table = 'main__user_weekends',
    $fillable = [
        'user_id',
        'division_id',
        'date_start',
        'date_end'
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
        ];
    }

    ### Связи
    ##################################################
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
