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
    Route::get('/',       [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login',  [AuthController::class, 'showLogin']);
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

    // ========================================================
    // Area Khusus Admin (Backend RBAC Validation)
    // ========================================================
    Route::middleware('admin')->group(function () {

        // Manajemen Absensi Manual (Hanya Admin yang berhak Tambah/Edit/Hapus)
        Route::get('/attendances/create',            [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/attendances',                  [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('/attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('/attendances/{attendance}',      [AttendanceController::class, 'update'])->name('attendances.update');
        Route::delete('/attendances/{attendance}',   [AttendanceController::class, 'destroy'])->name('attendances.destroy');

        // Manajemen Pegawai
        Route::resource('employees', EmployeeController::class);

        // Manajemen Departemen
        Route::resource('departments', DepartmentController::class);

        // Laporan Rekapitulasi Absensi
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/monthly',    [ReportController::class, 'monthly'])->name('monthly');
            Route::get('/department', [ReportController::class, 'byDepartment'])->name('department');
        });
    });

    // ========================================================
    // Absensi Umum & Mandiri (Semua User Terotentikasi)
    // ========================================================
    Route::get('/attendances',              [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/check-in',     [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/attendances/check-out',    [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
});
