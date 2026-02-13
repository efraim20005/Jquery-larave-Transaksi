<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\CategoryController;

Route::middleware(['auth','role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return "Dashboard Admin";
    })->name('admin.dashboard');

    Route::get('/admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/admin/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/admin/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/admin/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/admin/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/admin/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
});


/*
|--------------------------------------------------------------------------
| Kasir
|--------------------------------------------------------------------------
*/



Route::middleware(['auth','role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return "Dashboard Kasir";
    })->name('kasir.dashboard');

    Route::get('/kasir/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/kasir/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/kasir/history', [TransactionController::class, 'history'])
        ->name('transactions.history');

    Route::get('/kasir/history/{id}', [TransactionController::class, 'show'])
        ->name('transactions.show');
    Route::get('/kasir/history/{id}/pdf',
        [TransactionController::class, 'printPdf']
    )->name('transactions.print');



});



