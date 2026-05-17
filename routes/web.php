<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProductController::class, 'home'])->name('dashboard');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::controller(CartController::class)->group(function(){
    Route::post('/cart','index')->name('cart.index');
    Route::post('/cart/store/{product}','store')->name('cart.store');
    Route::put('/cart/{product}','updated')->name('cart.update');
    Route::delete('/cart/{product}','destroy')->name('cart.destroy');
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['verified'])->group(function(){
        Route::post('/cart/checkout',[CartController::class,'checkout'])->name('cart.checkout');
    });
});

require __DIR__ . '/auth.php';
