<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/qr/generate/{user}', [AttendanceController::class, 'generateQR'])->name('qr.generate');
    Route::post('/qr/scan', [AttendanceController::class, 'scanQR'])->name('scan.qr');
    Route::get('/qr/checkout/{id}', [AttendanceController::class, 'checkOut'])->name('qr.checkout');
    Route::get('/rekap', [AttendanceController::class, 'rekap'])->name('rekap');
    Route::resource('employees', EmployeeController::class);
});
