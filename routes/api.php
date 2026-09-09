<?php

use App\Http\ApiControllers\ApiServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/avalible-time',     [ApiServiceController::class, 'shedulesFromWorker'])->name('api.avalibleTime.index');
Route::get('/avalible-weekdays', [ApiServiceController::class, 'getAvailableWeekdays'])->name('api.availableWeekdays.index');
Route::get('/avalible-workers',  [ApiServiceController::class, 'workersFromService'])->name('api.availableWorkers.index');
