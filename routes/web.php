<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class);

    Route::get('/dashboards', DashboardController::class);

    Route::delete('/users/block/{user}', [UserController::class, 'block'])->name('users.block');
    Route::post('/users/change-password/{user}', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::resource('/users', UserController::class);

    Route::resource('/expenses', ExpenseController::class);
});

require __DIR__ . '/auth.php';
