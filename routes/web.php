<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use \App\Models\Product;

Route::get('/', function () {
    $products = Product::with('variants')->get();
    return view('welcome', compact('products'));
})->name('home');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('products.variants', VariantController::class);
});

// Cart routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
 
// Public product page
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{product}/view', function(Product $product) {
    $product->load('variants');
    return view('products.view', compact('product'));
})->name('product.view');

require __DIR__.'/auth.php';
