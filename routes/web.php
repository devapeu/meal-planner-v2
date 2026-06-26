<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShoppingListItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('shopping-list')->group(function () {
        Route::post('/', [ShoppingListItemController::class, 'store'])->name('shopping-list.store');
        Route::post('/reorder', [ShoppingListItemController::class, 'reorder'])->name('shopping-list.reorder');
        Route::put('/{shoppingListItem}', [ShoppingListItemController::class, 'update'])->name('shopping-list.update');
        Route::delete('/{shoppingListItem}', [ShoppingListItemController::class, 'destroy'])->name('shopping-list.destroy');
    });

});
