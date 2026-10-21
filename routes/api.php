<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/weather', [WeatherController::class, 'index']);

Route::get('/weather/health', [WeatherController::class, 'health']);

Route::get('/weather/{city}', [WeatherController::class, 'show']);

Route::post('/weather', [WeatherController::class, 'store']);