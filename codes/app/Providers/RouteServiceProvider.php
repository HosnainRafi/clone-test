<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // Register a macro to create routes for all site types
        Route::macro('adminRoutes', function ($callback) {
            // University routes
            Route::prefix('admin/university')->group($callback);

            // Department routes
            Route::prefix('admin/department')->group($callback);

            // Faculty routes
            Route::prefix('admin/faculty')->group($callback);

            // Legacy routes (backward compatibility)
            Route::prefix('admin')->group($callback);
        });
    }
}
