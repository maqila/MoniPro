<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\CustomerCollaborationController;
use App\Http\Controllers\UserManagementController;

// AUTH ROUTES
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

// ROUTES UNTUK SEMUA YANG LOGIN
Route::middleware(['auth'])->group(function () {

    // DASHBOARD - semua role bisa
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');

    // SETTING PROFILE - semua role bisa
    Route::get('/setting', [AuthController::class, 'edit']);
    Route::post('/setting-update', [AuthController::class, 'update']);
    Route::get('/change', [AuthController::class, 'change']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // USER = role 0: hanya boleh akses dashboard & setting (handled by exclusion)

    // ADMIN / MANAGER (role 1 & 2)
    Route::middleware(['auth', 'role:1,2,3'])->group(function () {

        // TIMELINE
        Route::post('timelines/{id}/done', [TimelineController::class, 'markAsDone'])->name('timelines.done');
        Route::get('/timeline/print', [TimelineController::class, 'print'])->name('timeline.print');
        Route::get('/timeline/export-pdf', [TimelineController::class, 'exportPdf'])->name('timeline.exportPdf');
        Route::resource('timeline', TimelineController::class);

        // CUSTOMER
        Route::resource('customer', CustomerController::class);
        Route::get('/customer/{id}/details', [CustomerController::class, 'details'])->name('customer.details');
        Route::get('/customer/{id}/print', [CustomerController::class, 'printPDF'])->name('customer.print');
        Route::get('/customer/{id}/print-filtered', [CustomerController::class, 'printFiltered'])->name('customer.printFiltered');

        // COLLABORATION
        Route::resource('collaboration', CollaborationController::class);
        Route::get('/collaborations/export-pdf', [CollaborationController::class, 'exportPdf'])->name('collaboration.exportPdf');

        // Collaboration update dari halaman customer detail
        Route::put('customer/collaboration/{collaboration}', [CustomerCollaborationController::class, 'update'])->name('customer.collaboration.update');
    });

    // SUPERADMIN ONLY (role 3)
    Route::middleware(['auth', 'role:3'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{id}/edit-role', [UserManagementController::class, 'editRole'])->name('users.editRole');
        Route::post('/users/{id}/update-role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');

        Route::get('/users/{id}/change-password', [UserManagementController::class, 'editPassword'])->name('users.editPassword');
        Route::post('/users/{id}/update-password', [UserManagementController::class, 'updatePassword'])->name('users.updatePassword');

        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});
