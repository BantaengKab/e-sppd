<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SPTController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // SPT routes
    Route::apiResource('spts', SPTController::class);
    Route::post('/spts/{spt}/submit', [SPTController::class, 'submit']);

    // Users (admin only)
    Route::middleware('admin')->group(function () {
        Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
    });

    // Estimated costs
    Route::apiResource('estimated-costs', \App\Http\Controllers\Api\EstimatedCostController::class);

    // SPPD routes
    Route::apiResource('sppds', \App\Http\Controllers\Api\SPPDController::class);
    Route::get('/sppds/{sppd}/pdf', [\App\Http\Controllers\Api\SPPDController::class, 'pdf']);

    // Realization routes
    Route::apiResource('realizations', \App\Http\Controllers\Api\RealizationController::class);

    // Approval routes
    Route::middleware(['supervisor', 'finance', 'verifikator'])->group(function () {
        Route::post('/spts/{spt}/approve', [\App\Http\Controllers\Api\ApprovalController::class, 'approve']);
        Route::get('/approvals', [\App\Http\Controllers\Api\ApprovalController::class, 'index']);
    });
});