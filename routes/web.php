<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Users
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/dashboard', function () { return Inertia::render('Dashboard');})
    ->middleware(['auth', 'verified'])->name('dashboard');

// Lists
Route::get('/lists', [ItemListController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('lists.index');
Route::get('/lists/{id}', [ItemListController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('lists.show');
Route::post('/lists', [ItemListController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('lists.store');
Route::put('/lists/{id}', [ItemListController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('lists.update');
Route::delete('/lists/{id}', [ItemListController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('lists.destroy');

// Items
Route::get('/lists/{listId}/items', [ItemController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('items.show');
Route::post('/lists/{listId}', [ItemController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('items.store');
Route::put('/items/{itemId}', [ItemController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('items.update');
Route::delete('/items/{itemId}', [ItemController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('items.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
