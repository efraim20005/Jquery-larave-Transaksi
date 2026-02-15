<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayoutController;
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

    Route::get('/dashboard',[LayoutController::class, 'index'])->name('dashboard');


    Route::get('/admin/produk', [ProductController::class, 'index'])->name('products.index');
    Route::post('/admin/produk/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/produk/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/admin/produk/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/produk/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');

    Route::get('/admin/kategori', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/admin/kategori/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/admin/kategori/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/admin/kategori/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/admin/kategori/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
});


/*
|--------------------------------------------------------------------------
| Kasir
|--------------------------------------------------------------------------
*/



Route::middleware(['auth','role:kasir'])->group(function () {


    Route::get('/kasir/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/kasir/transaksi/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/kasir/history', [TransactionController::class, 'history'])
        ->name('transactions.history');

    Route::get('/kasir/history/{id}', [TransactionController::class, 'show'])
        ->name('transactions.show');
    Route::get('/kasir/history/{id}/pdf',
        [TransactionController::class, 'printPdf']
    )->name('transactions.print');



});



