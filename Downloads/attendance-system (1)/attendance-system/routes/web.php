<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// =====================
// Autentikasi (Guest)
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// =====================
// Area Terproteksi (Auth)
// =====================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Absensi (semua user) ----
    Route::resource('attendances', AttendanceController::class);
    Route::post('/attendances/check-in',  [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');

    // ---- Admin Only ----
    Route::middleware('admin')->group(function () {

        // Pegawai
        Route::resource('employees', EmployeeController::class);

        // Departemen
        Route::resource('departments', DepartmentController::class);

        // Laporan
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/monthly',    [ReportController::class, 'monthly'])->name('monthly');
            Route::get('/department', [ReportController::class, 'byDepartment'])->name('department');
        });
    });
});
