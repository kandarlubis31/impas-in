<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\LeaderboardController;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/leaderboard', [LeaderboardController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete']);
    Route::post('/tasks/{id}/cancel', [TaskController::class, 'cancel']);
    
    Route::post('/tasks/{taskId}/offers', [OfferController::class, 'store']);
    Route::post('/offers/{id}/accept', [OfferController::class, 'accept']);
});