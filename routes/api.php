<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BearController;
use App\Http\Controllers\Api\V1\AuthController;

// Voorbeeld API route om de ingelogde gebruiker op te vragen
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
//     // Route::apiResource('bears', BearController::class);
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::apiResource('/bears', BearController::class);
//     Route::post('/register', [AuthController::class, 'register']);
// });

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum', 'throttle:2,2'])->group(function () {
        Route::apiResource('/bears', BearController::class);
    });
});