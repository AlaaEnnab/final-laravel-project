<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;

require __DIR__.'/auth.php';

// ============================
// Admin Routes
// ============================
Route::middleware(['auth', 'role:admin'])->group(function() {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vendors CRUD + attach/detach products
    Route::resource('vendors', VendorController::class);
    Route::post('vendors/{vendor}/attach-product', [VendorController::class, 'attachProduct'])->name('vendors.attachProduct');
    Route::post('vendors/{vendor}/detach-product', [VendorController::class, 'detachProduct'])->name('vendors.detachProduct');

    // Products CRUD (all actions)
    Route::resource('products', ProductController::class);

    // Orders management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// ============================
// Vendor Routes
// ============================
Route::middleware(['auth', 'role:vendor'])->group(function () {
    // Vendor can manage their own products (except index & show)
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});

// ============================
// Customer Routes
// ============================
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Orders for customer
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);

    // Checkout routes
    Route::prefix('checkout')->group(function() {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/thankyou/{order}', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');
        Route::get('/order/{order}', [CheckoutController::class, 'show'])->name('checkout.show');
    });
});

// ============================
// Authenticated User Routes
// ============================
Route::middleware(['auth'])->group(function () {
    // Dashboard (common for all roles)
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // Profile management
    Route::prefix('profile')->group(function() {
        Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
