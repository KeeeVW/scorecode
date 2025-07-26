<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScoutController;
use App\Http\Middleware\ScoutMiddleware;

// Welcome Page
Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('do_login');
Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'createUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/impersonate', [AdminController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/stop-impersonating', [AdminController::class, 'stopImpersonating'])->name('stop-impersonating');
    Route::post('/users/{user}/block', [AdminController::class, 'blockUser'])->name('users.block');
    Route::post('/users/{user}/unblock', [AdminController::class, 'unblockUser'])->name('users.unblock');
    Route::get('/scout-profiles/{user}', [AdminController::class, 'showScoutProfile'])->name('scout.profiles.show');
    
    // Scout Management
    Route::prefix('scout')->name('scout.')->group(function () {
        // Uniform
        Route::get('/uniform', [AdminController::class, 'uniform'])->name('uniform');
        Route::post('/uniform', [AdminController::class, 'storeUniform'])->name('uniform.store');
        Route::put('/uniform/{record}', [AdminController::class, 'updateUniform'])->name('uniform.update');
        Route::delete('/uniform/{record}', [AdminController::class, 'deleteUniform'])->name('uniform.delete');
        
        // Korasa
        Route::get('/korasa', [AdminController::class, 'korasa'])->name('korasa');
        Route::post('/korasa', [AdminController::class, 'storeKorasa'])->name('korasa.store');
        Route::put('/korasa/{id}', [AdminController::class, 'updateKorasa'])->name('korasa.update');
        Route::delete('/korasa/{id}', [AdminController::class, 'deleteKorasa'])->name('korasa.delete');
        
        // Other Scout Routes
        Route::get('/badges', [AdminController::class, 'scoutBadges'])->name('badges');
        Route::get('/points', [AdminController::class, 'points'])->name('points');
        Route::get('/attendance', [AdminController::class, 'scoutAttendance'])->name('attendance');
        Route::get('/scoreboard', [AdminController::class, 'scoreboard'])->name('scoreboard');
    });
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/settings/backup', [AdminController::class, 'downloadBackup'])->name('settings.backup');
    Route::get('/settings/export-users/{format}', [AdminController::class, 'exportUsers'])->name('settings.export_users');
    Route::post('/scoreboard', [AdminController::class, 'manageScoreboard'])->name('scoreboard.update');
    Route::post('/locks', [AdminController::class, 'updateLocks'])->name('locks.update');

    // Uniform Management Routes
    Route::post('/uniform/{user_id}', [AdminController::class, 'storeUniform'])->name('uniform.store');
    Route::put('/uniform/{recordId}', [AdminController::class, 'updateUniform'])->name('uniform.update');
    Route::delete('/uniform/{recordId}', [AdminController::class, 'deleteUniform'])->name('uniform.delete');

    // Korasa Management Routes
    Route::post('/korasa/{user_id}', [AdminController::class, 'storeKorasa'])->name('korasa.store');
    Route::put('/korasa/{recordId}', [AdminController::class, 'updateKorasa'])->name('korasa.update');
    Route::delete('/korasa/{recordId}', [AdminController::class, 'deleteKorasa'])->name('korasa.delete');

    // Badge Management Routes
    Route::post('/badges/{user_id}', [AdminController::class, 'storeBadge'])->name('badges.store');
    Route::put('/badges/{recordId}', [AdminController::class, 'updateBadge'])->name('badges.update');
    Route::delete('/badges/{recordId}', [AdminController::class, 'deleteBadge'])->name('badges.delete');

    // Points Management Routes
    Route::post('/points/{user_id}', [AdminController::class, 'storePoints'])->name('points.store');
    Route::put('/points/{recordId}', [AdminController::class, 'updatePoints'])->name('points.update');
    Route::delete('/points/{recordId}', [AdminController::class, 'deletePoints'])->name('points.delete');

    // Attendance Management Routes
    Route::post('/attendance/{user_id}', [AdminController::class, 'storeAttendance'])->name('attendance.store');
    Route::put('/attendance/{recordId}', [AdminController::class, 'updateAttendance'])->name('attendance.update');
    Route::delete('/attendance/{recordId}', [AdminController::class, 'deleteAttendance'])->name('attendance.delete');

    // Locks Management Routes
    Route::post('/locks/{user_id}', [AdminController::class, 'storeLock'])->name('locks.store');
    Route::put('/locks/{recordId}', [AdminController::class, 'updateLock'])->name('locks.update');
    Route::delete('/locks/{recordId}', [AdminController::class, 'deleteLock'])->name('locks.delete');

    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Reports sub-features
    Route::get('/reports/events', [AdminController::class, 'reportsEvents'])->name('reports.events');
    Route::get('/reports/custom-query', [AdminController::class, 'reportsCustomQuery'])->name('reports.custom_query');
    Route::get('/reports/charts', [AdminController::class, 'reportsCharts'])->name('reports.charts');
});

// Admin broadcast scoreboard message (outside group to avoid double prefix)
Route::post('/admin/scoreboard/broadcast', [App\Http\Controllers\AdminController::class, 'broadcastScoreboard'])->name('admin.scoreboard.broadcast');

// Admin delete scoreboard message
Route::delete('/admin/scoreboard/message/{id}', [App\Http\Controllers\AdminController::class, 'deleteScoreboardMessage'])->name('admin.scoreboard.delete');

// Scout Routes
Route::middleware(['auth', ScoutMiddleware::class])->prefix('scout')->name('scout.')->group(function () {
    Route::get('/uniform', [ScoutController::class, 'uniform'])->name('uniform');
    Route::get('/korasa', [ScoutController::class, 'korasa'])->name('korasa');
    Route::get('/badges', [ScoutController::class, 'badges'])->name('badges');
    Route::get('/points', [ScoutController::class, 'points'])->name('points');
    Route::get('/attendance', [ScoutController::class, 'attendance'])->name('attendance');
    Route::get('/total', [ScoutController::class, 'total'])->name('total');
    Route::get('/scoreboard', [ScoutController::class, 'scoreboard'])->name('scoreboard');
    Route::get('/positions', [ScoutController::class, 'positions'])->name('positions');
    Route::get('/locks', [ScoutController::class, 'locks'])->name('locks');
    Route::get('/profile', [\App\Http\Controllers\ScoutController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [ScoutController::class, 'dashboard'])->name('dashboard');
}); 

Route::post('/admin/scoreboard/custom-broadcast', [App\Http\Controllers\AdminController::class, 'customBroadcastScoreboard'])->name('admin.scoreboard.custom_broadcast');