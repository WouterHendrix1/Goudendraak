<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KassaZoekController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\KlantController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/pdf', [MenuController::class, 'pdf'])->name('menu.pdf');
Route::get('/nieuws', [NewsController::class, 'index'])->name('nieuws');
Route::get('/contact', function () {return view('/contact.index');})->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');
Route::get('/kassa/zoek', [KassaZoekController::class, 'index'])->name('kassa.zoek')
->middleware(['auth', 'admin']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('admin/bestellingen')->name('admin.orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/create', [AdminOrderController::class, 'create'])->name('create');
        Route::post('/store', [AdminOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/delen', [AdminOrderController::class, 'delen'])->name('delen');
        Route::post('/{id}/delen', [AdminOrderController::class, 'storeDelen'])->name('storeDelen');
        Route::get('/{id}/pdf', [AdminOrderController::class, 'pdf'])->name('pdf');
    });

    Route::get('/tafel/{tafel}/bestelling', [AdminOrderController::class, 'createFromTafel'])->name('tafel.bestelling.create');


    Route::prefix('admin/gerechten')->name('admin.dishes.')->group(function () {
        Route::get('/', [DishController::class, 'index'])->name('index');
        Route::get('/create', [DishController::class, 'create'])->name('create');
        Route::post('/', [DishController::class, 'store'])->name('store');
        Route::get('/{id}', [DishController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DishController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DishController::class, 'update'])->name('update');
        Route::delete('/{id}', [DishController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/nieuws')->name('admin.news.')->group(function () {
        Route::get('/', [NewsController::class, 'adminIndex'])->name('index');
        Route::get('/create', [NewsController::class, 'create'])->name('create');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::get('/{id}', [NewsController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/verkoop')->name('admin.sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
    });

});

require __DIR__.'/auth.php';
