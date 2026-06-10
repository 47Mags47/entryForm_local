<?php

use App\Http\ApiControllers\ApiServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/avalible-time', [ApiServiceController::class, 'shedulesFromWorker'])->name('api.avalibleTime.index');
