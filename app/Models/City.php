<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\Admin\CityFactory> */
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected
        $table = 'main__cities',
        $fillable = [
            'name',
        ];

}
