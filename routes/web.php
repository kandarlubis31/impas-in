<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LandingController;

Route::get('/', [LandingController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/tasks', [DashboardController::class, 'storeTask']);
    Route::post('/tasks/{taskId}/offer', [DashboardController::class, 'sendOffer']);
    Route::post('/offers/{offerId}/accept', [DashboardController::class, 'acceptOffer']);
    Route::post('/tasks/{taskId}/complete', [DashboardController::class, 'completeTask']);
    Route::post('/tasks/{taskId}/cancel', [DashboardController::class, 'cancelTask']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
