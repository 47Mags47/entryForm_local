<?php

use App\Http\ApiControllers\ApiWorkersController;
use Illuminate\Support\Facades\Route;

Route::get('/avalible-time',                [ApiWorkersController::class, 'shedulesFromWorker'])->name('api.avalibleTime.index');
Route::get('/avalible-weekdays',            [ApiWorkersController::class, 'getAvailableWeekdays'])->name('api.availableWeekdays.index');
Route::get('/avalible-workers',             [ApiWorkersController::class, 'workersFromService'])->name('api.availableWorkers.index');
Route::get('/avalible-workers-from-dates',  [ApiWorkersController::class, 'workersFromDates'])->name('api.availableWorkersFromDates.index');
