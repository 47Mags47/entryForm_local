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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::user()) {
        if (user()->role_id == UserRole::byCode('admin')->id) {
            return redirect()->route('divisions.index');
        }

        return redirect()->route('events.index', ['division' => user()->division->id]);
    } else
        return redirect()->route('login');
})->name('home');

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->middleware('guest')->name('login');
    Route::post('/login', 'store')->middleware('guest')->name('auhtificate');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::resource('/division/{division}/events', EventCalendarController::class)
        ->only(['index']);

    Route::resource('/services', ServiceController::class)
        ->except(['show']);

    Route::resource('/cities', CityController::class)
        ->except(['show']);

    Route::resource('/division-group', DivisionGroupController::class)
        ->except(['show']);

    Route::resource('/divisions', DivisionController::class);

    Route::get('/division/{division}/subscribes/export', [SubscribesExportController::class, 'index'])
        ->name('subscribes.export');
    Route::resource('/division/{division}/subscribes', SubscribeController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::resource('/statistic', StatisticController::class)
        ->only(['index']);

    Route::resource('/divisions/{division}/division-admins', DivisionAdminController::class)
        ->only(['store', 'destroy']);

    Route::resource('/divisions/{division}/workers', WorkerController::class)
        ->only(['index']);

    Route::resource('/invites', UserInviteController::class)
        ->only(['create', 'store']);

    Route::resource('/workers', WorkerController::class)
        ->only(['edit', 'update', 'destroy']);

    Route::resource('/dashboard/user', DashboardController::class)
        ->only(['show', 'edit', 'update']);

    Route::resource('/{division}/frame', FrameController::class)
        ->except(['show', 'create', 'edit']);
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
