<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

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

Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'role:vendeur'])->prefix('vendeur')->name('vendor.')->group(function () {
    Route::resource('produits', VendorProductController::class)->parameters(['produits' => 'product'])->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/panier/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/commande', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/commande', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/commandes/{order}/confirmer-paiement', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/vendeurs', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::patch('/vendeurs/{vendor}/approuver', [AdminVendorController::class, 'approve'])->name('vendors.approve');
    Route::patch('/vendeurs/{vendor}/rejeter', [AdminVendorController::class, 'reject'])->name('vendors.reject');
        Route::patch('/commandes/{order}/statut', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/commandes', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/produits', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/produits/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/produits/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/produits/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

    Route::post('/produits/{product}/avis', [ReviewController::class, 'store'])->name('reviews.store');

require __DIR__.'/auth.php';