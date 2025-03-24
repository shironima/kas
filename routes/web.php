<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactNotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RTController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AdminRTController;
use App\Http\Controllers\DashboardRTController;
use App\Http\Controllers\TrashBinController;
use App\Http\Controllers\TrashBinSuperAdminController;

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
Route::prefix('super_admin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/trash-bin', [TrashBinSuperAdminController::class, 'index'])->name('superadmin.trashbin.index');
    Route::patch('/trash-bin/restore/{type}/{id}', [TrashBinSuperAdminController::class, 'restore'])->name('superadmin.trashbin.restore');
    Route::delete('/trash-bin/delete/{type}/{id}', [TrashBinSuperAdminController::class, 'forceDelete'])->name('superadmin.trashbin.forceDelete');
    
    // Route untuk manajemen contact notifications
    Route::resource('contact_notifications', ContactNotificationController::class)->except(['create', 'show']);
    Route::put('/contact_notifications/{id}/toggle', [ContactNotificationController::class, 'toggleNotification'])->name('contact_notifications.toggle');

    // Route untuk manajemen keuangan
    Route::resource('finance', FinanceController::class)->except(['create', 'show']);

    // Route untuk laporan keuangan (filter & eksport)
    Route::prefix('finance')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('finance.index');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/data', [ReportController::class, 'getData'])->name('report.data');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('report.export');
        Route::get('/export-rt-analysis-pdf', [ReportController::class, 'exportRTAnalysisPDF'])->name('report.rt_analysis');
        Route::get('/reports/show', [ReportController::class, 'showReport'])->name('reports.show');
        Route::get('/reports/export', [ReportController::class, 'exportPDF'])->name('reports.export');
    });

    // Route untuk manajemen RT -> bikin data tentang RT
    Route::resource('rt', RTController::class)->except(['show', 'create', 'edit']);

    // Route untuk manajemen akun role : admin_rt (CRUD akun admin RT)
    Route::resource('admin-rt', AdminRTController::class);
    Route::get('/admin-rt/data', [AdminRTController::class, 'getData'])->name('admin-rt.data');

    // Route untuk manajemen kategori
    Route::resource('categories', CategoryController::class)->except(['create', 'show']);
});

Route::prefix('admin-rt')->middleware(['auth', 'role:admin_rt'])->group(function () {
    Route::get('/dashboard', [DashboardRTController::class, 'index'])->name('dashboardRT');

    Route::get('/trash-bin', [TrashBinController::class, 'index'])->name('trash-bin.index');
    Route::patch('/restore/{type}/{id}', [TrashBinController::class, 'restore'])->name('trash-bin.restore');
    Route::delete('/delete/{type}/{id}', [TrashBinController::class, 'forceDelete'])->name('trash-bin.forceDelete');

    Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('expenses/deleted', [ExpenseController::class, 'deleted'])->name('expenses.deleted'); // soft delete
    Route::put('expenses/{id}/restore', [ExpenseController::class, 'restore'])->name('expenses.restore'); // restore
    Route::delete('expenses/{id}/force-delete', [ExpenseController::class, 'forceDelete'])->name('expenses.forceDelete'); // force delete

    Route::resource('incomes', IncomeController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

