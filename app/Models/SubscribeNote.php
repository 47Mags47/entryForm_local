<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribeNote extends Model
{
    protected $table = 'main__subscribe_notes';

    protected $fillable = [
        'worker_id',
        'subscribe_id',
        'note',
    ];
}
