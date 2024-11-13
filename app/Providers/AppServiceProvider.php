<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
          // Registra un hook para que se muestre el contenido debajo del top-bar
          Filament::serving(function () {
            // Solo ejecuta el render hook si no estamos en la página de login
            if (auth()->check() && !request()->is('login')) {
                Filament::registerRenderHook('filament::top-bar.after', function () {
                    return view('components.top-bar-info');
                });
            }
        });
    }
}
