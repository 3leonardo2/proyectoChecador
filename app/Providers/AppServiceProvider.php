<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Registrar rutas API manualmente
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}