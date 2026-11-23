<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index']);
// Authentication Routes
Route::get('auth/login', [AuthController::class, 'login'])->name('login'); 
Route::get('auth/register', [AuthController::class, 'register'])->name('register'); 
Route::post('auth/store', [AuthController::class, 'store'])->name('auth.store'); 
Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout'); 
Route::post('auth/login/store', [AuthController::class, 'loginStore'])->name('auth.loginStore'); 
Route::get('auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgotPassword');
Route::post('auth/forgot-password', [AuthController::class, 'sendResetLink'])->name('auth.password.email');
Route::get('auth/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('auth.password.reset');
Route::post('auth/reset-password', [AuthController::class, 'updatePassword'])->name('auth.password.update');


// Frontend Routes 
Route::get('/home', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('index');

Route::middleware(['auth', 'role:admin,api'])->prefix('admin')->name('admin.')->group(function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});