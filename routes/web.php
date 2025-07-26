<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('is_admin')->group(function () {
    Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/product/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::post('/admin/product/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->name('admin.products.delete');
});

Route::middleware('user')->group(function () {
    Route::get('/products',  [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart', action: [CartController::class, 'index'])->name('cart.index');
});


require __DIR__ . '/auth.php';
