<?php

use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\AssetLogController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\BorrowPageController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [DashboardController::class, 'index'])->name('home');

// Auth pages
Route::get('/login', fn () => inertia('Auth/Login'))->name('login')->middleware('guest');
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

// Dev quick-login (local only)
Route::get('/dev/login-as-admin', function () {
    abort_unless(app()->isLocal(), 403);
    $user = \App\Models\User::where('email', 'wongnarin.s@msu.ac.th')->firstOrFail();
    Auth::login($user, remember: true);
    return redirect()->route('home');
});

// Auth-protected routes
Route::middleware('auth')->group(function () {
    // Inventory
    Route::get('/inventory', [EquipmentController::class, 'index'])->name('inventory');
    Route::post('/inventory', [EquipmentController::class, 'store'])->name('inventory.store');

    // Borrow request page
    Route::get('/borrow', [BorrowPageController::class, 'index'])->name('borrow');
    Route::post('/borrow', [BorrowRequestController::class, 'store'])->name('borrow.store');
    Route::post('/borrow/quick', [BorrowRequestController::class, 'quickStore'])->name('borrow.quick');

    // Tracking & asset logs
    Route::get('/tracking', [AssetLogController::class, 'index'])->name('tracking');
    Route::post('/tracking/log', [AssetLogController::class, 'store'])->name('tracking.log');

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    // History
    Route::get('/history', [BorrowRequestController::class, 'index'])->name('history');

    // Admin: return
    Route::get('/admin/return', [ReturnController::class, 'index'])->name('admin.return');
    Route::patch('/admin/return/{id}', [ReturnController::class, 'update'])->name('admin.return.update');
    Route::post('/admin/return/quick', [ReturnController::class, 'quickReturn'])->name('admin.return.quick');
});
