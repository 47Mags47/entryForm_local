<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscribeNote extends Model
{
    protected $table = 'main__subscribe_notes';

    protected $fillable = [
        'worker_id',
        'subscribe_id',
        'note',
    ];

    public function subscribe(): BelongsTo
    {
        return $this->belongsTo(Subscribe::class, 'subscribe_id', 'id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
}
