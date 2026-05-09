<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProductListsController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [UserController::class, 'index'])->name('home.index');
Route::get('/products', [ProductListsController::class, 'index'])->name('product.index');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'redirectAdmin'])->name('dashboard');

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'show')->name('cart.show');
    Route::post('/cart/{product}', 'store')->name('cart.store');
    Route::PUT('/cart/{product}', 'update')->name('cart.update');
    Route::DELETE('/cart/{product}', 'destroy')->name('cart.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/address/update', [UserController::class, 'updateShippingAddress'])->name('address.update');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'stripeCancel'])->name('checkout.cancel');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutForOrder'])->name('checkout.order');
});
        
// Group of Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::PUT('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::DELETE('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.delete');
    
    Route::delete('/product_images/{image}', [ProductImagesController::class, 'destroy'])->name('admin.product_images.delete');
    Route::post('/products/{product}/images', [ProductImagesController::class, 'store'])->name('admin.product_images.store');
    
});

Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function(){
    //admin Login
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.post.login');
    //Application admin initialize
    Route::get('register-with-key', [AdminAuthController::class, 'createPrimaryAdmin'])
    ->name('registerKeyAdmin');
});



Route::group(['prefix' => 'admin'], function(){
    //admin  register routes
    Route::get('register', [AdminAuthController::class, 'create'])
    ->name('registerAdmin');
    Route::post('register', [AdminAuthController::class, 'store']);
    Route::resource('category', CategoryController::class);
    Route::resource('brands', BrandsController::class);
});



//Authentication Routes login, registration, logout...
require __DIR__.'/auth.php';
