<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer
    Route::middleware('role:customer')->group(function () {
        Route::get('/history', [DashboardController::class, 'history'])->name('history');
    });

    // Admin & Cashier
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/pos', [TransactionController::class, 'pos'])->name('pos.index');
        Route::post('/pos/checkout', [TransactionController::class, 'checkout'])->name('pos.checkout');
        Route::get('/invoice/{transaction}', [TransactionController::class, 'invoice'])->name('invoice.show');
        
        Route::post('/products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
        
        Route::resource('products', ProductController::class)->except(['destroy']);
    });

    // Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/reports', [TransactionController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [TransactionController::class, 'export'])->name('reports.export');
        // Staff management
        Route::get('/admin/staff', [StaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/admin/staff', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::delete('/admin/staff/{user}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');
    });
});

require __DIR__.'/auth.php';
