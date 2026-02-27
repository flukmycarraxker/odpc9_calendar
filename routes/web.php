<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MeetingController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| CALENDAR (HOME)
|--------------------------------------------------------------------------
*/
Route::get('/', [MeetingController::class, 'index'])
    ->name('calendar.index');

/*
|--------------------------------------------------------------------------
| PASSWORD RESET
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password',
    [ForgotPasswordController::class, 'showLinkRequestForm']
)->name('password.request');

Route::post('/forgot-password',
    [ForgotPasswordController::class, 'sendResetLinkEmail']
)->name('password.email');

Route::get('/reset-password/{token}',
    [ForgotPasswordController::class, 'showResetForm']
)->name('password.reset');

Route::post('/reset-password',
    [ForgotPasswordController::class, 'reset']
)->name('password.update');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | MEETINGS (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::post('/meetings',
        [MeetingController::class, 'store']
    )->name('meetings.store');

    Route::put('/meetings/{meeting}',
        [MeetingController::class, 'update']
    )->name('meetings.update');

    Route::delete('/meetings/{meeting}',
        [MeetingController::class, 'destroy']
    )->name('meetings.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        Route::get('/admin/users',
            [AdminUserController::class, 'index']
        )->name('admin.users');

        Route::post('/admin/users/update-role/{id}',
            [AdminUserController::class, 'updateRole']
        )->name('admin.users.updateRole');

    });
});