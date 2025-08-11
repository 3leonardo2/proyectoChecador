<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        URL::forceRootUrl(config('app.url'));
        Schema::defaultStringLength(191);

        if (config('app.env') === 'local') {
            URL::forceScheme('http');
        }
        // Registrar rutas API manualmente
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
            
    }
}