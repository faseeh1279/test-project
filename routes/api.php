<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\AuthController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/weather/health', [WeatherController::class, 'health']);

Route::middleware('auth:api')->prefix('weather')->group(function () {
    Route::get('/', [WeatherController::class, 'index'])
        ->middleware('permission:view weather');

    Route::get('/{city}', [WeatherController::class, 'show'])
        ->middleware('permission:view weather');

    Route::post('/', [WeatherController::class, 'store'])
        ->middleware('role_or_permission:admin|create weather');
});



// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected authentication routes (requires valid Bearer token on the 'api' guard)
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Example role- and permission-protected routes using Spatie + JWT 'api' guard
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Admin access granted.']);
    });
});

Route::middleware(['auth:api', 'permission:edit posts'])->group(function () {
    Route::put('/posts/{post}', function ($post) {
        return response()->json(['message' => "Post {$post} updated."]);
    });
});