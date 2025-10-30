<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardWebController;
use App\Http\Controllers\SPTWebController;
use App\Http\Controllers\ProfileWebController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RealizationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\EstimatedCostController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

    // SPT routes
    Route::prefix('spts')->name('spts.')->group(function () {
        Route::get('/', [SPTWebController::class, 'index'])->name('index');
        Route::get('/create', [SPTWebController::class, 'create'])->name('create');
        Route::post('/', [SPTWebController::class, 'store'])->name('store');
        Route::get('/{spt}', [SPTWebController::class, 'show'])->name('show');
        Route::get('/{spt}/edit', [SPTWebController::class, 'edit'])->name('edit');
        Route::put('/{spt}', [SPTWebController::class, 'update'])->name('update');
        Route::post('/{spt}/submit', [SPTWebController::class, 'submit'])->name('submit');
        Route::delete('/{spt}', [SPTWebController::class, 'destroy'])->name('destroy');
    });

    // SPPD routes
    Route::prefix('sppds')->name('sppds.')->group(function () {
        Route::get('/{sppd}', [SPTWebController::class, 'showSppd'])->name('show');
        Route::get('/{sppd}/download', [SPTWebController::class, 'downloadPdf'])->name('download');
        Route::post('/{sppd}/complete', [RealizationController::class, 'completeSPPD'])->name('complete');
    });

    // Approval routes (supervisor, finance, verifikator, admin)
    Route::prefix('approvals')->name('approvals.')->middleware(['role:supervisor,finance,verifikator,admin'])->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/history', [ApprovalController::class, 'history'])->name('history');
        Route::get('/{spt}', [ApprovalController::class, 'show'])->name('show');
        Route::post('/{spt}', [ApprovalController::class, 'approve'])->name('approve');
    });

    // Realization routes
    Route::prefix('realizations')->name('realizations.')->group(function () {
        Route::post('/', [RealizationController::class, 'store'])->name('store');
        Route::put('/{realization}', [RealizationController::class, 'update'])->name('update');
        Route::delete('/{realization}', [RealizationController::class, 'destroy'])->name('destroy');
        Route::get('/{realization}/download', [RealizationController::class, 'downloadFile'])->name('download');
    });

    // User management (admin only)
    Route::middleware(['role:admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::get('/{user}', [UsersController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [UsersController::class, 'bulkAction'])->name('bulk');
        Route::get('/export', [UsersController::class, 'export'])->name('export');
    });

    // Activity Log (admin only)
    Route::middleware(['role:admin'])->prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::get('/statistics', [ActivityLogController::class, 'statistics'])->name('statistics');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::post('/cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
        Route::post('/bulk-delete', [ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Estimated Costs management
    Route::middleware(['auth'])->prefix('estimated-costs')->name('estimated-costs.')->group(function () {
        Route::get('/', [EstimatedCostController::class, 'index'])->name('index');
        Route::get('/create', [EstimatedCostController::class, 'create'])->name('create');
        Route::post('/', [EstimatedCostController::class, 'store'])->name('store');
        Route::get('/{estimatedCost}', [EstimatedCostController::class, 'show'])->name('show');
        Route::get('/{estimatedCost}/edit', [EstimatedCostController::class, 'edit'])->name('edit');
        Route::put('/{estimatedCost}', [EstimatedCostController::class, 'update'])->name('update');
        Route::delete('/{estimatedCost}', [EstimatedCostController::class, 'destroy'])->name('destroy');
        Route::post('/duplicate', [EstimatedCostController::class, 'duplicate'])->name('duplicate');
        Route::post('/bulk-action', [EstimatedCostController::class, 'bulkAction'])->name('bulk');
        Route::get('/export', [EstimatedCostController::class, 'export'])->name('export');
    });

    // Profile routes
    Route::get('/profile', [ProfileWebController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileWebController::class, 'update'])->name('profile.update');
    // Add Breeze-compatible destroy route
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Original Breeze profile routes (keep for compatibility)
    Route::get('/profile/breeze', [ProfileController::class, 'edit'])->name('profile.breeze.edit');
    Route::patch('/profile/breeze', [ProfileController::class, 'update'])->name('profile.breeze.update');
    Route::delete('/profile/breeze', [ProfileController::class, 'destroy'])->name('profile.breeze.destroy');
});

require __DIR__.'/auth.php';
