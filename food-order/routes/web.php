<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;

require __DIR__.'/auth.php';
Route::middleware(['auth','role:admin'])->group(function() {
    Route::resource('vendors', VendorController::class);
    Route::post('vendors/{vendor}/attach-product', [VendorController::class, 'attachProduct'])->name('vendors.attachProduct');
    Route::post('vendors/{vendor}/detach-product', [VendorController::class, 'detachProduct'])->name('vendors.detachProduct');
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
  
  Route::resource('products', ProductController::class); // كل المنتجات + إدارة كاملة
});

// Route::middleware(['auth', 'role:admin'])->group(function() {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
// });

Route::middleware(['auth','role:vendor'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index','show']); 
});


Route::middleware(['auth','role:customer'])->group(function () {
    Route::resource('orders', OrderController::class)->only(['index','create','store','show']);
});


Route::middleware(['auth','role:customer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/thankyou/{order}', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');
    Route::get('/checkout/order/{order}', [CheckoutController::class, 'show'])->name('checkout.show');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
});


Route::middleware(['auth'])->group(function() {
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});


