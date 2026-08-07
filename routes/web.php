<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionGroupController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\DivisionAdminController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SubscribesExportController;
use App\Http\Controllers\UserInviteController;
use App\Http\Controllers\WorkerController;
use App\Models\UserRole;
use Illuminate\Support\Facades\Route;

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->middleware('guest')->name('login');
    Route::post('/login', 'store')->middleware('guest')->name('auhtificate');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('divisions.index');
    })->name('home');

    // NEED DIVISION
    Route::middleware('hasDivision')->group(function () {
        Route::resource('/divisions', DivisionController::class);

        Route::prefix('/divisions/{division}')->group(function () {
            Route::resource('/events', EventCalendarController::class)
                ->only(['index']);

            Route::get('/subscribes/export', [SubscribesExportController::class, 'index'])
                ->name('subscribes.export');
            Route::Resource('/subscribes', SubscribeController::class)
                ->except(['edit', 'update'])
                ->withTrashed(['show', 'destroy']);

            Route::resource('/division-admins', DivisionAdminController::class)
                ->only(['store']);

            Route::get('/workers/{worker}/restore', [WorkerController::class, 'restore'])
                ->name('workers.restore')
                ->withTrashed();
            Route::resource('/workers', WorkerController::class);

            Route::resource('/frame', FrameController::class)
                ->except(['show', 'create', 'edit']);
        });
    });

    // NOT NEED DIVISION
    Route::resource('/statistic', StatisticController::class)
        ->only(['index']);

    Route::resource('/services', ServiceController::class)
        ->except(['show']);

    Route::resource('/cities', CityController::class)
        ->except(['show']);

    Route::resource('/division-group', DivisionGroupController::class)
        ->except(['show']);

    Route::resource('/invites', UserInviteController::class)
        ->only(['create', 'store']);

    Route::resource('/dashboard/user', DashboardController::class)
        ->only(['show', 'edit', 'update']);
});


Route::middleware('guest')->group(function () {
    Route::get('/invites/{token}/accept', [UserInviteController::class, 'accept'])->name('invites.accept');
    Route::get('/workers/create/{token}', [WorkerController::class, 'create'])->name('workers.create');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
});


Route::controller(SecurityController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPasswordGet')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'forgotPasswordPost')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', 'passwordResetGet')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'passwordResetPost')->middleware('guest')->name('password.update');

    Route::get('/change-password', 'passwordChangeGet')->middleware('auth')->name('passwordChange.edit');
    Route::put('/change-password/{user}', 'passwordChangePut')->middleware('auth')->name('passwordChange.update');

    Route::get('/change-email/user/{token}/accept', 'accept')->name('change-email.accept');
    Route::get('/change-email', 'changeEmailGet')->middleware('auth')->name('change-email');
    Route::post('/change-email', 'changeEmailPost')->middleware('auth')->name('change-email.post');
});
