<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiteController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

// Admin Dashboard Routes
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    // Sites CRUD Routes
    Route::prefix('sites')->name('sites.')->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('index');
        Route::get('/create', [SiteController::class, 'create'])->name('create');
        Route::post('/', [SiteController::class, 'store'])->name('store');
        Route::get('/{id}', [SiteController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SiteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SiteController::class, 'update'])->name('update');
        Route::delete('/{id}', [SiteController::class, 'destroy'])->name('destroy');
    });
});

// Admin API Routes
Route::prefix('api/admin')->name('api.admin.')->middleware(['admin.auth'])->group(function () {
    
    // Sites API
    Route::prefix('sites')->name('sites.')->group(function () {
        Route::get('/', [SiteController::class, 'apiIndex']);
        Route::get('/{id}', [SiteController::class, 'apiShow']);
        Route::post('/', [SiteController::class, 'apiStore']);
        Route::put('/{id}', [SiteController::class, 'apiUpdate']);
        Route::delete('/{id}', [SiteController::class, 'apiDestroy']);
        Route::get('/themes/list', [SiteController::class, 'getThemes']);
        Route::post('/validate-domain', [SiteController::class, 'validateDomain']);
        Route::patch('/{id}/toggle-status', [SiteController::class, 'toggleStatus']);
        Route::post('/validate-menu-items', [SiteController::class, 'validateMenuItems']);
    });
});