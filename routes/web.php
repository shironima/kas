<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ContactNotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RTController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AdminRTController;

Route::get('/', function () {
    return redirect('/login');
});

// Login & Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Protected Routes
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route untuk manajemen contact notifications
    Route::resource('contact_notifications', ContactNotificationController::class)->except(['create', 'show']);
    Route::put('/contact_notifications/{id}/toggle', [ContactNotificationController::class, 'toggleNotification'])->name('contact_notifications.toggle');

    // Route untuk manajemen pemasukan
    Route::resource('income', IncomeController::class)->except(['create', 'show']);

    // Route untuk manajemen pengeluaran
    Route::resource('expense', ExpenseController::class)->except(['create', 'show']);

    // Route untuk manajemen keuangan
    Route::resource('finance', FinanceController::class)->except(['create', 'show']);

    // Route untuk laporan keuangan (filter & eksport)
    Route::prefix('finance/reports')->group(function () {
        Route::get('/', [FinanceController::class, 'generateReport'])->name('finance.report.generate');
        Route::get('/export/excel', [FinanceController::class, 'exportExcel'])->name('finance.report.excel');
        Route::get('/export/pdf', [FinanceController::class, 'exportPdf'])->name('finance.report.pdf');
    });

    // Route untuk manajemen RT
    Route::resource('rt', RTController::class)->except(['show', 'create', 'edit']);

    // Route untuk manajemen akun role : admin_rt
    Route::resource('admin-rt', AdminRTController::class);
    Route::get('/admin-rt/data', [AdminRTController::class, 'getData'])->name('admin-rt.data');

    // Route untuk manajemen kategori
    Route::resource('categories', CategoryController::class)->except(['create', 'show']);
});

Route::middleware(['auth', 'role:admin_rt'])->group(function () {
    Route::get('/dashboard/admin-rt', [AdminRTController::class, 'index'])->name('admin-rt.dashboard');

    Route::resource('expenses-rt', ExpenseController::class);

    Route::resource('incomes-rt', IncomeController::class);
});

